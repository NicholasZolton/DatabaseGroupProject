<?php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

header("Access-Control-Allow-Headers: *");
define("DataBaseLocation","127.0.0.1");
define("DBUserName","root");
define("DBPassword","");
define("NameOfDatabase","forassignment8");

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

    $userN = $_POST["Username"];
    $pwd = $_POST["Password"];
  
    $result = login($userN,$pwd);
    echo json_encode(["UserID"=>$result],JSON_PRETTY_PRINT);

?>