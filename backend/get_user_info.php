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

    $userID = $_GET["userID"];

    $result = getUserInfo($userID);
    if(is_string($result)){
        echo "FAIL";
    }
    else{
        echo json_encode($result,JSON_PRETTY_PRINT);
    }

?>