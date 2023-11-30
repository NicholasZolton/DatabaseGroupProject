<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

header("Access-Control-Allow-Headers: *");
define("DataBaseLocation","127.0.0.1");
define("DBUserName","root");
define("DBPassword","");
define("NameOfDatabase","forassignment8");
function getUserInfo($UserID){
    $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
    if ($conn->connect_error) { 
        die("Connection failed: " . $conn->connect_error); 
    }
    $sql = "SELECT UserName,Email,DateOfBirth
            FROM user
            WHERE User_ID = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i",$UserID);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0){
            return "FAILURE: Ticket_ID Not Found";
        }
        $stmt->bind_result($userN,$email,$DOB);
        $stmt->fetch();
        $result = ["UserName"=>$userN,"Email"=>$email,"DOB"=>$DOB];
        return $result;
    }
}
function getVenueInfo($venueID){
    $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
    if ($conn->connect_error) { 
        die("Connection failed: " . $conn->connect_error); 
    }
    $sql = "SELECT VenueName,Capacity,StreetAddress,VenueCity,VenueState,Zip_Code
            FROM venue
            WHERE Venue_ID = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i",$venueID);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0){
            return "FAILURE: Ticket_ID Not Found";
        }
        $stmt->bind_result($VenueN,$Capac,$StrAddr,$VenCity,$VenState,$Zip);
        $stmt->fetch();
        $result = ["VenueName"=>$VenueN,"Capacity"=>$Capac,"StreetAddress"=>$StrAddr,"VenueCity"=>$VenCity,"VenueState"=>$VenState,"Zip_Code"=>$Zip];
        return $result;
    }
}
function getEventInfo($eventID){
    $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
    if ($conn->connect_error) { 
        die("Connection failed: " . $conn->connect_error); 
    }
    $sql = "SELECT EventName,EventType,EventTime,EventDate,Age_Limit,Venue_ID
            FROM `event`
            WHERE Event_ID = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i",$eventID);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0){
            return "FAILURE: Ticket_ID Not Found";
        }
        $stmt->bind_result($EventN,$EventTy,$EventTi,$EventD,$AgeLim,$venueID);
        $stmt->fetch();
        $result = ["EventName"=>$EventN,"EventType"=>$EventTy,"EventTime"=>$EventTi,"EventDate"=>$EventD,"Age_Limit"=>$AgeLim,"Venue_ID"=>$venueID];
        return $result;
    }
}
function getTicketInfo($ticketID){
    $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
    if ($conn->connect_error) { 
        die("Connection failed: " . $conn->connect_error); 
    }
    $sql =  "SELECT Event_ID,Venue_ID,Owner_ID
             FROM ticket
             WHERE Ticket_ID = ? ;";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i",$ticketID);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows == 0){
            return "FAILURE: Ticket_ID Not Found";
        }
        $stmt->bind_result($event_ID,$venue_ID,$owner_ID);
        $stmt->fetch();
        $eventInfo = getEventInfo($event_ID);
        $venueInfo = getVenueInfo($venue_ID);
        $ownerInfo = getUserInfo($owner_ID);
        $result = array_merge($eventInfo,$venueInfo,$ownerInfo,array("Owner_ID"=>$owner_ID,"Event_ID"=>$event_ID));
        #$result = push_array($result, $ownerInfo); 
        $stmt -> close();
        return $result;
    }
            
}
function getAvailableTickets(){
    $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
    if ($conn->connect_error) { 
        die("Connection failed: " . $conn->connect_error); 
    }
    $sql = "SELECT T.Ticket_ID, T.Price
            FROM tickets_in_order AS T, `order` AS O
            WHERE O.Order_ID = T.Order_ID AND O.Buyer_ID IS NULL;";
    if($stmt = $conn->prepare($sql)){
        $stmt -> execute();
        $stmt -> store_result();
        if($stmt->num_rows == 0){
            return array();//returns an empty array if no tickets are available
        }
        $stmt->bind_result($ticketID,$price);
        $result = array();
        while($stmt->fetch()){
            $toAdd = ["Ticket_ID"=>$ticketID,"Price"=>$price];
            array_push($result,$toAdd);
        }
        return $result;
    }

}
function createOrder($owner, $buyer){
    $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
    if ($conn->connect_error) { 
        die("Connection failed: " . $conn->connect_error); 
    }
    $createOrder = "INSERT INTO `order`
                    VALUES (null,?,?)";
    if($createStmt = $conn->prepare($createOrder)){
        $createStmt->bind_param("ii",$owner,$buyer);
        $createStmt->execute();
        if(mysqli_affected_rows($conn) == 0){
            return "FAILURE:Unable to add order";
        }
    }
    $getOrderID = "SELECT LAST_INSERT_ID();";
    if($getStmt = $conn->prepare($getOrderID)){
        $getStmt -> execute();
        $getStmt->bind_result($orderID);
        $getStmt->store_result();
        $getStmt->fetch();
        return $orderID;
    }
  } 

    class InvalidDatabaseException extends Exception {};
    /*INPUT: Array in the form ["Ticket_ID"=>ticket to be bought,"Buyer_ID" => id of user who is buying the ticket
                                "Order_ID" => order to add the ticket too]
             Leave Order_ID out or set to null if a new order is to be created.
      OUTPUT: Order_ID where Order_ID is the order the ticket was added to or FAILURE:Message if ticket can not be bought
    */
    function buyTicket($paramArray){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $ticket_ID = $paramArray["Ticket_ID"];
        $buyer_ID = $paramArray["Buyer_ID"];
        $price = null;
        $orderID = null;
        $ownerID = null;
        if(array_key_exists("Order_ID",$paramArray)){
            $orderID = $paramArray["Order_ID"];
        }
        //is ticket available?
        $availTickets = getAvailableTickets();
        $isAvail = false;
        foreach ($availTickets as $ticket){
            $price = $ticket["Price"];
            $currTick = $ticket["Ticket_ID"];
            $ticketInfo = getTicketInfo($currTick);
            if($currTick == $ticket_ID){
                $ownerID = $ticketInfo["Owner_ID"];
                if($ownerID == $buyer_ID){
                    return "FAILURE";
                }
                $isAvail = true;
                $price = $ticket["Price"];
            }
        }
        if(!$isAvail){
            return "FAILURE: Ticket not for sell";
        }
        //is user a buyer?
        $isBuyer = "SELECT User_ID
                    FROM   buyer
                    WHERE  User_ID = ? AND Card_Number IS NOT NULL AND Card_Expr IS NOT NULL AND Security_Code IS NOT NULL;";
        if($isBuyerStmt = $conn->prepare($isBuyer)){
            $isBuyerStmt->bind_param("i",$buyer_ID);
            $isBuyerStmt->execute();
            $isBuyerStmt->store_result();
            if($isBuyerStmt->num_rows == 0){
                return "FAILURE: User is not a buyer or does not have card info";
            }
        }
        //get sellOrder that ticket is in
        $findSellOrder = "SELECT Order_ID
                          FROM   `order`
                          WHERE  Seller_ID = ? AND Buyer_ID IS NULL";
        if($findSellStmt = $conn->prepare($findSellOrder)){
            $findSellStmt->bind_param("i",$ownerID);
            $findSellStmt->execute();
            $findSellStmt->bind_result($sellOrderID);
            $findSellStmt->store_result();
            $findSellStmt->fetch();
        }
        //get price of ticket
        $findPriceTicket = "SELECT Price
                            FROM   tickets_in_order
                            WHERE  Ticket_ID = ? AND Order_ID = ?";
        if($findStmt = $conn->prepare($findPriceTicket)){
            $findStmt->bind_param("ii",$ticket_ID,$sellOrderID);
            $findStmt->execute();
            $findStmt->store_result();
            if($findStmt->num_rows == 0){
                return "FAILURE: Unable to get price";
            }
            $findStmt->bind_result($price);
            $findStmt->fetch();
        }
        $conn->begin_transaction();
        try{
            //remove ticket from sellOrder
            $removeTicket = "DELETE FROM tickets_in_order
                             WHERE  Order_ID = ? AND Ticket_ID = ?";
            if($removeStmt = $conn->prepare($removeTicket)){
                $removeStmt->bind_param("ii",$sellOrderID,$ticket_ID);
                $removeStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    throw new InvalidDatabaseException();
                }
            }
            //change owner of ticket to buyer
            $changeOwner = "UPDATE ticket
                            SET Owner_ID = ?
                            WHERE Ticket_ID = ?";
            if($changeStmt = $conn->prepare($changeOwner)){
                $changeStmt->bind_param("ii",$buyer_ID,$ticket_ID);
                $changeStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    throw new InvalidDatabaseException();
                }
            }
            //create new order if orderID was not specified
            if(is_null($orderID)){
                $orderID = createOrder($ownerID,$buyer_ID);
                if(is_string($orderID)){
                    return $orderID;
                }
            }
            //add to order
            $addToOrder = "INSERT INTO tickets_in_order
                           VALUES (?,?,?)";
            if($addStmt = $conn->prepare($addToOrder)){
                $addStmt->bind_param("iid",$ticket_ID,$orderID,$price);
                $addStmt->execute();
                if(mysqli_affected_rows($conn) == 0){
                    throw new InvalidDatabaseException();
                }
            }
            $conn->commit();
        }
        catch(InvalidDatabaseException $exception){
            $conn->rollback();
            return "FAILURE Transaction Unable to Complete";
        }
        catch(mysqli_sql_exception $exception){
            $conn->rollback();
            return "FAILURE Transaction Unable to Complete";
        }
        $conn->close();
        return $orderID;
      }

      $ticketID = $_POST["TicketID"];
      $buyerID = $_POST["BuyerID"];
      
     
      $result = buyTicket(["Ticket_ID" => $ticketID, "Buyer_ID" => $buyerID]);
      

      echo json_encode(["OrderID"=>$result],JSON_PRETTY_PRINT);
?>