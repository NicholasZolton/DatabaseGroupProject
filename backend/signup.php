<?php
    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    header("Access-Control-Allow-Headers: *");
    define("DataBaseLocation","127.0.0.1");
    define("DBUserName","root");
    define("DBPassword","");
    define("NameOfDatabase","forassignment8");

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
                    return "FAILURE";
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

        $userN = $_POST["Username"];
        $pwd = $_POST["Password"];
        $email = $_POST["Email"];
        $DOB = $_POST["DOB"];

        $result = signUp(["UserN" => $userN, "PassW" => $pwd, "Email" => $email, "DOB"   => $DOB]);
        if($result == "FAILURE"){
            $result = null;
        }
        echo json_encode(["UserID"=>$result],JSON_PRETTY_PRINT);


     ?>