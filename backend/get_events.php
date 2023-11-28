<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

header("Access-Control-Allow-Headers: *");

define("DataBaseLocation","127.0.0.1");
define("DBUserName","root");
define("DBPassword","");
define("NameOfDatabase","forassignment8");

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
        $venueInfo = getVenueInfo($venueID);
        $eventInfo = ["EventName"=>$EventN,"EventType"=>$EventTy,"EventTime"=>$EventTi,"EventDate"=>$EventD,"Age_Limit"=>$AgeLim];
        $result = array_merge($venueInfo,$eventInfo);
        $result = array_merge(["EventID" => $eventID],$result);
        return $result;
    }
}


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
            array_push($result, getEventInfo($eventID));
        }
        echo json_encode($result,JSON_PRETTY_PRINT);
        ?>