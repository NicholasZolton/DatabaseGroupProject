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
    function sellTicket($sellerID,$ticketID,$price){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $availTickets = getAvailableTickets();
        foreach ($availTickets as $ticket){
            if($ticket["Ticket_ID"] == $ticketID){
                return "FAILURE: Ticket already for sell";
            }
        }
        $ticketInfo = getTicketInfo($ticketID);
        if(is_string($ticketInfo)){
            return "FAILURE: Ticket does not exist";
        }
        if($ticketInfo["Owner_ID"]!= $sellerID){
            return "FAILURE: Can not sell someone else's ticket";
        }
        
        $isSeller = "SELECT User_ID
                     FROM seller
                     WHERE User_ID = ? ;";
        if($isSellerStmt = $conn->prepare($isSeller)){
            $isSellerStmt->bind_param("i",$sellerID);
            $isSellerStmt->execute();
            $isSellerStmt->store_result();
            if($isSellerStmt->num_rows == 0){
                addSeller($sellerID, 2);
            }
        }
        $hasSellOrder = "SELECT Order_ID
                         FROM `order`
                         WHERE Seller_ID = ? AND Buyer_ID IS NULL;";
        if($hasSellOrderStmt = $conn->prepare($hasSellOrder)){
            $hasSellOrderStmt->bind_param("i", $sellerID);
            $hasSellOrderStmt->execute();
            $hasSellOrderStmt->store_result();
            if($hasSellOrderStmt->num_rows == 0){
                $makeSellOrder = "INSERT INTO `order`
                                  VALUES (null, ?, null)";
                if($makeSellStmt = $conn->prepare($makeSellOrder)){
                    $makeSellStmt->bind_param("i",$sellerID);
                    $makeSellStmt->execute();
                }
            }
            $hasSellOrderStmt->execute(); 
            $hasSellOrderStmt->bind_result($orderID);
            $hasSellOrderStmt->store_result();
            $hasSellOrderStmt->fetch();
            $insertSellOrder = "INSERT INTO tickets_in_order
                                VALUES (?,?,?)";
            if($insertSellStmt = $conn->prepare($insertSellOrder)){
                $insertSellStmt->bind_param("iid",$ticketID,$orderID,$price);
                $insertSellStmt->execute();
                if(mysqli_affected_rows($conn) > 0 ){
                    return "SUCCESS";
                }
            }
        }
        
    }

    function addSeller($userID,$rating){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $addToSeller = "INSERT INTO seller
                        VALUES (?,?);";
        if($addToSellerStmt = $conn->prepare($addToSeller)){
            $addToSellerStmt->bind_param($userID,$rating);
            $addToSellerStmt->execute();
            if(mysqli_affected_rows($conn) == 0 ){
                return "FAILURE";
            }
            return "SUCCESS";
        }
    }

      $ticketID = $_POST["TicketID"];
      $sellerID = $_POST["SellerID"];
      $price = $_POST["Price"];
      
      $result = sellTicket($sellerID,$ticketID,$price);
      
      echo json_encode(["Result"=>$result],JSON_PRETTY_PRINT);
?>