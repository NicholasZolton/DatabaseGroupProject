<?php
    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    header("Access-Control-Allow-Headers: *");
    define("DataBaseLocation","127.0.0.1");
    define("DBUserName","root");
    define("DBPassword","");
    define("NameOfDatabase","forassignment8");

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

        $userID = $_POST["UserID"];
        
        $tickets = getUsersTickets($userID);
        $result = [];
        foreach ($tickets as $ticket){
            $ticketInfo = getTicketInfo($ticket);
            $subresult = array_merge(["TicketID"=>$ticket], $ticketInfo);
            array_push($result, $subresult);
        }
        echo json_encode($result,JSON_PRETTY_PRINT);


     ?>