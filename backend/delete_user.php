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
        $sql = "DELETE FROM user
                WHERE User_ID = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("i",$UserID);
            $stmt->execute();
            $stmt->store_result();
            if(mysqli_affected_rows($conn) == 0){
                return "FAILURE: User_ID Not Found";
            }
            return "Success";
        }
    }

    $userID = $_POST["userID"];

    $result = getUserInfo($userID);
    
    echo json_encode(["Result"=>$result],JSON_PRETTY_PRINT);
    

?>