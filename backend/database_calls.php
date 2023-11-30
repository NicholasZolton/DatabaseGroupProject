<?php
    define("DataBaseLocation","127.0.0.1");
    define("DBUserName","root");
    define("DBPassword","");
    define("NameOfDatabase","forassignment8");

    /*INPUT: Username, Password
      OUTPUT: User_ID if user exists, null otherwise
    */
    function login($userN, $pwd){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $sql = "SELECT  User_ID
        FROM    USER
        WHERE   UserName = ? AND PassW = ?;";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ss", $userN, $pwd);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows == 0){
                $result = null;
            }
            else{
                $stmt->bind_result($userID);
                $stmt->fetch();
                $result = $userID;
            }
        }
        $conn->close();
        return $result;
    }
    /*INPUT: Array in the form ["UserN" => value of User's Username, "PassW" => value of User's password,
                                "Email" => value of User's email   , "DOB"   => value of User's Date of birth]
      OUTPUT: User_ID of newly created user, if UserN/Email already exists will return "FAILURE: UserN/Email is not unique"
    */
    function signUp($paramArray){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $userN = $paramArray["UserN"];
        $passW = $paramArray["PassW"];
        $email = $paramArray["Email"];
        $DOB = $paramArray["DOB"];
        //check if userN or email exists
        $checkSql = "SELECT  User_ID
                     FROM    USER
                     WHERE   UserName = ? OR Email = ?;";
        if($checkStmt = $conn->prepare($checkSql)){
            $checkStmt->bind_param("ss",$userN,$email);
            $checkStmt->execute();
            $checkStmt->store_result();
            if($checkStmt->num_rows != 0){
                return "FAILURE: UserName or Email is not unique";
            }
            $checkStmt -> close();
        }
        //inser info into DB
        $sql = "INSERT INTO user
                VALUES (null,?,?,?,?);";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssss",$userN,$email,$passW,$DOB);
            $stmt->execute();
            //if it worked return the user_ID
            if(mysqli_affected_rows($conn) > 0 ){
                $getUserID =  "SELECT  User_ID
                               FROM    USER
                               WHERE   UserName = ? AND Passw = ?;";
                if($findStmt = $conn->prepare($getUserID)){
                    $findStmt -> bind_param("ss",$userN,$passW);
                    $findStmt -> execute();
                    $findStmt -> bind_result($userID);
                    $findStmt -> fetch();
                    $result = $userID;
                    $findStmt -> close();
                }
            }
            else{
            $result = "FAILURE";
            }
            $stmt -> close();
        }
        $conn->close();
        return $result;
    }
    /*INPUT: Array in the form ["UserID"=> value of User's userID to change info, "UserN" => value of User's Username, 
                                "PassW" => value of User's password,              "Email" => value of User's email,
                                "DOB"   => value of User's Date of birth]
      OUTPUT: SUCESS if info is updated, FAILURE otherwise
    */
    function editUserInfo($paramArray){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $userID = $paramArray["UserID"];
        $userN = $paramArray["UserN"];
        $passW = $paramArray["PassW"];
        $email = $paramArray["Email"];
        $DOB = $paramArray["DOB"];
        $result = "";
        if(!empty($userN) || !empty($email)){
            $checkSql = "SELECT  User_ID
                         FROM    USER
                         WHERE   UserName = ? OR Email = ?;";
            if($checkStmt = $conn->prepare($checkSql)){
                $checkStmt->bind_param("ss",$userN,$email);
                $checkStmt->execute();
                $checkStmt->store_result();
                if($checkStmt->num_rows != 0){
                    return "FAILURE: UserName or Email is not unique. User:".$gotUserID;
                }
                $checkStmt -> close();
            }
        }
        if(!empty($userN)){
            $updateSql = "UPDATE user
                           SET UserName = ?
                           WHERE User_ID = ?";
             if($updateStmt = $conn->prepare($updateSql)){
                $updateStmt->bind_param("ss",$userN,$userID);
                $updateStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    return "FAILURE";
                }
                $result = $result."CHANGED USERNAME";
                $updateStmt->close();
            }
        }
        if(!empty($passW)){
            $updateSql = "UPDATE user
                           SET PassW = ?
                           WHERE User_ID = ?";
             if($updateStmt = $conn->prepare($updateSql)){
                $updateStmt->bind_param("ss",$passW,$userID);
                $updateStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    return "FAILURE";
                }
                $result = $result." CHANGED PASSWORD";
                $updateStmt->close();
            }
        }
        if(!empty($email)){
            $updateSql = "UPDATE user
                           SET Email = ?
                           WHERE User_ID = ?";
             if($updateStmt = $conn->prepare($updateSql)){
                $updateStmt->bind_param("ss",$email,$userID);
                $updateStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    return "FAILURE";
                }
                $result = $result." CHANGED EMAIL";
                $updateStmt->close();
            }
        }
        if(!empty($DOB)){
            $updateSql = "UPDATE user
                           SET DateOfBirth = ?
                           WHERE User_ID = ?";
             if($updateStmt = $conn->prepare($updateSql)){
                $updateStmt->bind_param("ss",$DOB,$userID);
                $updateStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    return "FAILURE";
                }
                $result = $result." CHANGED DOB";
                $updateStmt->close();
            }
        }
        $conn->close();
        return $result;
    }
    /*INPUT: Array in the form ["UserID"=> value of User's userID to change info, "CardN" => value of User's Username, 
                                "CardExpr" => value of User's password,              "SecCode" => value of User's email]
      OUTPUT: SUCESS if info is updated, FAILURE otherwise
    */
    function editBuyerInfo($paramArray){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $userID = $paramArray["UserID"];
        $cardN = $paramArray["CardN"];
        $cardEx = $paramArray["CardExpr"];
        $cardSec = $paramArray["SecCode"];
        $result = "";

        if(!empty($cardN)){
            $updateSql = "UPDATE buyer
                           SET Card_Number = ?
                           WHERE User_ID = ?";
             if($updateStmt = $conn->prepare($updateSql)){
                $updateStmt->bind_param("ss",$cardN,$userID);
                $updateStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    return "FAILURE";
                }
                $result = $result."CHANGED CARD NUMBER";
                $updateStmt->close();
            }
        }
        if(!empty($cardEx)){
            $updateSql = "UPDATE buyer
                           SET Card_Expr = ?
                           WHERE User_ID = ?";
             if($updateStmt = $conn->prepare($updateSql)){
                $updateStmt->bind_param("ss",$cardEx,$userID);
                $updateStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    return "FAILURE";
                }
                $result = $result." CHANGED EXPRDATE";
                $updateStmt->close();
            }
        }
        if(!empty($cardSec)){
            $updateSql = "UPDATE buyer
                           SET Security_Code = ?
                           WHERE User_ID = ?";
             if($updateStmt = $conn->prepare($updateSql)){
                $updateStmt->bind_param("ss",$cardSec,$userID);
                $updateStmt->execute();
                if(mysqli_affected_rows($conn) == 0 ){
                    return "FAILURE";
                }
                $result = $result." CHANGED SECURCODE";
                $updateStmt->close();
            }
        }
        $conn->close();
        return $result;
    }
    /*INPUT: Event_ID
      OUTPUT: Array in the form ["EventName"=>Name of event,  "EventType"=>Type of event     ,  "EventTime"=>Time of event,
                                 "EventDate"=>Date of event,  "Age_Limit"=>Age limit of event,   "Venue_ID"=>Venue of event]
    */
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
    /*INPUT: VenueID
     OUTPUT: Array in the form ["VenueName"=>Name of venue,  "Capacity"=>Capacity of venue     ,  "StreetAddress"=>Street Address of venue,
                                "VenueCity"=>City of venue,  "VenueState"=>State of venue     ,   "Zip_Code"=>Zipcode of venue]
    */
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
    /*INPUT:  User_ID
      OUTPUT: Array in the form ["UserName"=> UserName of user, "Email" => email of user, 
                                "DOB" => value of User's password]
    */
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
    /*INPUT:  TicketID
      OUTPUT: Array in the form ["EventName"=>Name of event,       "EventType"=>Type of event     ,"EventTime"=>Time of event,
                                 "EventDate"=>Date of event,       "Age_Limit"=>Age limit of event,"Venue_ID"=>Venue of event,
                                 "VenueName"=>Name of venue,       "Capacity"=>Capacity of venue,  "StreetAddress"=>Street Address of venue,
                                 "VenueCity"=>City of venue,       "VenueState"=>State of venue,   "Zip_Code"=>Zipcode of venue,
                                 "Owner_ID"=> User_ID of owner,    "UserName"=> UserName of user,  "Email" => email of user,
                                 "DOB" => value of User's password,"
                                 
    */
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
    /*INPUT:  TicketID
      OUTPUT: Array in the form ["0"=> [id of Ticket0, price] , "1"=> [id of Ticket1,price], ... , "N"=> [id of TicketN, price]]
              Where each element holds the ID of a ticket and the price of that ticket
    */
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
    /*INPUT: sellerID, ticketID where selleId is the one who is selling ticketID
      OUTPUT: success/failure depending on if ticket is able to be sold
    */
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
    /*INPUT: userID, rating where userID is the one to add to the seller table and rating is their rating
      OUTPUT: sucess/failure depending on if they are able to be added 
      Will result in an error if user is already a seller*/
      
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
    /*INPUT: Array in the form ["UserID"=> value of User's userID to add to buyer table,"Card_Number" => value of User's Card_number, 
                                "Card_Expr" => value of User's CardExpr,                "Security_Code" => value of User's security code]
      OUTPUT: SUCESS if user is added, FAILURE otherwise
      Will result in an error if user is already a buyer
    */
      function addBuyer($paramArray){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $userID = $paramArray["User_ID"];
        $cardN = null;
        $cardE = null;
        $cardS = null;
        if(array_key_exists("Card_Number",$paramArray)){
            $cardN = $paramArray["Card_Number"];
        }
        if(array_key_exists("Card_Expr",$paramArray)){
            $cardE = $paramArray["Card_Expr"];
        }
        if(array_key_exists("Security_Code",$paramArray)){
            $cardS = $paramArray["Security_Code"];
        }
        $addToSeller = "INSERT INTO buyer
                        VALUES (?,?,?,?);";
        if($addToSellerStmt = $conn->prepare($addToSeller)){
            $addToSellerStmt->bind_param("isss",$userID,$cardN,$cardE,$cardS);
            $addToSellerStmt->execute();
            if(mysqli_affected_rows($conn) == 0 ){
                return "FAILURE";
            }
            return "SUCCESS";
        }
    }
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
            echo $currTick.":";
            $ticketInfo = getTicketInfo($currTick);
            if($currTick == $ticket_ID){
                $ownerID = $ticketInfo["Owner_ID"];
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
        //remove ticket from sellOrder
        $removeTicket = "DELETE FROM tickets_in_order
                         WHERE  Order_ID = ? AND Ticket_ID = ?";
        if($removeStmt = $conn->prepare($removeTicket)){
            $removeStmt->bind_param("ii",$sellOrderID,$ticket_ID);
            $removeStmt->execute();
            if(mysqli_affected_rows($conn) == 0 ){
                return "FAILURE: Not able to remove from sell order";
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
                return "FAILURE: Not able to change owner of ticket";
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
                return "FAILURE: Not able to add ticket to order";
            }
        }
        $conn->close();
        return $orderID;
      }
    /*INPUT: owner, buyer where owner is the userID of the seller and buyer is the user id of the buyer
      OUTPUT: Order_ID which is the Order_ID of the order that was created
      */ 
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
    /*INPUT: venue, event, owner
      OUTPUT: Ticket_ID which is the Ticket_ID of the ticket that was created*/
      function createTicket($event,$venue,$owner){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $checkExists = "SELECT *
                        FROM venue V, `event` E, `user` O
                        WHERE V.Venue_ID = ? AND E.Event_ID = ? AND O.User_ID = ?";
        if($checkStmt = $conn->prepare($checkExists)){
            $checkStmt->bind_param("iii",$venue,$event,$owner);
            $checkStmt->execute();
            $checkStmt->store_result();
            if($checkStmt->num_rows == 0){
                return "FAILURE: Invalid ID for Venue or Event or User";
            }
        }
        $addTicket = "INSERT INTO ticket
                      VALUES (null,?,?,?)";
        if($addStmt = $conn->prepare($addTicket)){
            $addStmt->bind_param("iii",$event,$venue,$owner);
            $addStmt->execute();
            $addStmt->store_result();
            if(mysqli_affected_rows($conn) == 0){
                return "FAILURE: unable to create ticket";
            }
        }
        $getTicketID = "SELECT LAST_INSERT_ID();";
        if($getStmt = $conn->prepare($getTicketID)){
            $getStmt -> execute();
            $getStmt->bind_result($ticketID);
            $getStmt->store_result();
            $getStmt->fetch();
            return $ticketID;
        }

      }
      /*INPUT: filterAttr, filterVal where filterAttr is the attribute to filter ticket info on and filterVal is the value of the attribute to filter the ticket on.
        OUTPUT: Array in the form ["0"=> [id of Ticket0, price] , "1"=> [id of Ticket1,price], ... , "N"=> [id of TicketN, price]]
                Where each element holds the ID of a ticket and the price of that ticket and all tickets are for sell
      */
      function getAvailTicketsBy($filterAttr,$filterVal){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $tickets = getAvailableTickets();
        $result = array();
        foreach ($tickets as $ticket){
            $ticketID = $ticket["Ticket_ID"];
            $ticketInfo = getTicketInfo($ticketID);
            if($ticketInfo[$filterAttr] == $filterVal)
                array_push($result, $ticket);
        }
        return $result;
      }
      /*INPUT: userID where userID is the id of the user whose tickets will be returned
        OUTPUT: Array in the form ["0"=> id of Ticket0 , "1"=> id of Ticket1, ... , "N"=> id of TicketN]
                Where each element holds the ID of a ticket that belongs to userID
      */
      function getUsersTickets($userID){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $result = array();
        $getTickets = "SELECT Ticket_ID
                       FROM   ticket
                       WHERE Owner_ID = ?";
        if($getStmt = $conn->prepare($getTickets)){
            $getStmt->bind_param("i",$userID);
            $getStmt->execute();
            $getStmt->bind_result($ticketID);
            while($getStmt->fetch())
                array_push($result,$ticketID);
            return $result;
        }

      }
       /*INPUT: orderID where orderID is the id of the order whose tickets will be returned
        OUTPUT: Array in the form ["0"=> id of Ticket0 , "1"=> id of Ticket1, ... , "N"=> id of TicketN]
                Where each element holds the ID of a ticket that belongs to orderID
      */
      function getTicketsInOrder($orderID){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $sql = "SELECT T.Ticket_ID, T.Price
                FROM tickets_in_order T, `order` AS O
                WHERE T.Order_ID = O.Order_ID AND O.Order_ID = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt -> bind_param("i",$orderID);
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
       /*
         INPUT: Array in the form ["VenueName"=>Name of venue,  "Capacity"=>Capacity of venue     ,  "StreetAddress"=>Street Address of venue,
                                "VenueCity"=>City of venue,  "VenueState"=>State of venue     ,   "Zip_Code"=>Zipcode of venue]
         OUTPUT: VenueID where VenueID is the ID of the venue that was just created
      */
      function addVenue($paramArray){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $venueN = null;
        $venueCap = null;
        $venueStre = null;
        $venueCity = null;
        $venueState = null;
        $venueZ = null;
        if(array_key_exists("VenueName",$paramArray)){
            $venueN = $paramArray["VenueName"];
        }
        if(array_key_exists("Capacity",$paramArray)){
            $venueCap = $paramArray["Capacity"];
        }
        if(array_key_exists("StreetAddress",$paramArray)){
            $venueStre = $paramArray["StreetAddress"];
        }
        if(array_key_exists("VenueCity",$paramArray)){
            $venueCity = $paramArray["VenueCity"];
        }
        if(array_key_exists("VenueState",$paramArray)){
            $venueState = $paramArray["VenueState"];
        }
        if(array_key_exists("Zip_Code",$paramArray)){
            $venueZ = $paramArray["Zip_Code"];
        }
        $addToVenue = "INSERT INTO venue
                        VALUES (null,?,?,?,?,?,?);";
        if($addToVenueStmt = $conn->prepare($addToVenue)){
            $addToVenueStmt->bind_param("sisssi",$venueN,$venueCap,$venueStre,$venueCity,$venueState,$venueZ);
            $addToVenueStmt->execute();
            if(mysqli_affected_rows($conn) == 0 ){
                return "FAILURE";
            }
            $getVenueID = "SELECT LAST_INSERT_ID();";
            if($getStmt = $conn->prepare($getVenueID)){
                $getStmt -> execute();
                $getStmt->bind_result($venueID);
                $getStmt->store_result();
                $getStmt->fetch();
                return $venueID;
            }
        }
    }
           /*
         INPUT:  Array in the form ["EventName"=>Name of event,  "EventType"=>Type of event     ,  "EventTime"=>Time of event,
                                    "EventDate"=>Date of event,  "Age_Limit"=>Age limit of event,   "Venue_ID"=>Venue of event]
         OUTPUT: EventID where EventID is the ID of the event that was just created
      */
      function addEvent($paramArray){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $eventN = null;
        $eventTy = null;
        $eventTi = null;
        $eventD = null;
        $ageL = null;
        $venueID = null;
        if(array_key_exists("EventName",$paramArray)){
            $eventN = $paramArray["EventName"];
        }
        if(array_key_exists("EventType",$paramArray)){
            $eventTy = $paramArray["EventType"];
        }
        if(array_key_exists("EventTime",$paramArray)){
            $eventTi = $paramArray["EventTime"];
        }
        if(array_key_exists("EventDate",$paramArray)){
            $eventD = $paramArray["EventDate"];
        }
        if(array_key_exists("Age_Limit",$paramArray)){
            $ageL = $paramArray["Age_Limit"];
        }
        if(array_key_exists("Venue_ID",$paramArray)){
            $venueID = $paramArray["Venue_ID"];
        }
        $addToEvent = "INSERT INTO `event`
                        VALUES (null,?,?,?,?,?,?);";
        if($addToEventStmt = $conn->prepare($addToEvent)){
            $addToEventStmt->bind_param("ssssii",$eventN,$eventTy,$eventTi,$eventD,$ageL,$venueID);
            $addToEventStmt->execute();
            if(mysqli_affected_rows($conn) == 0 ){
                return "FAILURE";
            }
            $getEventID = "SELECT LAST_INSERT_ID();";
            if($getStmt = $conn->prepare($getEventID)){
                $getStmt -> execute();
                $getStmt->bind_result($eventID);
                $getStmt->store_result();
                $getStmt->fetch();
                return $eventID;
            }
        }
    }
    /*   INPUT: filterAttr, filterVal where filterAttr is the attribute to filter event info on and filterVal is the value of the attribute to filter the event on.
        OUTPUT: Array in the form ["0"=> id of event0 , "1"=> id of event1, ... , "N"=> id of eventN]
                Where each element holds the ID of an event*/
    function getEventBy($filterAttr,$filterVal){  
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        //get all events
        $events = array();
        $getEvents = "SELECT *
                      FROM `event`";
        $eventResult = $conn->query($getEvents);
        if($eventResult->num_rows > 0){
            while($row = $eventResult->fetch_assoc()){
                array_push($events,$row);
            }
            $eventResult->free();
        }
        //filter based on parameters
        $result = array();
        foreach ($events as $event){
            $eventID = $event["Event_ID"];
            //if($event[$filterAttr] == $filterVal)
            array_push($result, $eventID);
        }
        return $result;
    }
    /*   INPUT: filterAttr, filterVal where filterAttr is the attribute to filter venue info on and filterVal is the value of the attribute to filter the venue on.
        OUTPUT: Array in the form ["0"=> id of venue0 , "1"=> id of venue1, ... , "N"=> id of venueN]
                Where each element holds the ID of a venue*/
    function getVenueBy($filterAttr,$filterVal){  
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        //get all events
        $venues = array();
        $getVenues = "SELECT *
                      FROM venue";
        $venueResult = $conn->query($getVenues);
        if($venueResult->num_rows > 0){
            while($row = $venueResult->fetch_assoc()){
                array_push($venues,$row);
            }
            $venueResult->free();
        }
        //filter based on parameters
        $result = array();
        foreach ($venues as $venue){
            $venueID = $venue["Venue_ID"];
            if($venue[$filterAttr] == $filterVal)
                array_push($result, $venueID);
        }
        return $result;
    }



?>