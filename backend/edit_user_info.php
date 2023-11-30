<?php
    header('Access-Control-Allow-Origin: *');

    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

    header("Access-Control-Allow-Headers: *");

    define("DataBaseLocation","127.0.0.1");
    define("DBUserName","root");
    define("DBPassword","");
    define("NameOfDatabase","forassignment8");

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
                $checkStmt->bind_result($gotUserID);
                $checkStmt->fetch();
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
    function addBuyer($paramArray){
        $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
        if ($conn->connect_error) { 
            die("Connection failed: " . $conn->connect_error); 
        }
        $userID = $paramArray["UserID"];
        $cardN = null;
        $cardE = null;
        $cardS = null;
        if(array_key_exists("CardN",$paramArray)){
            $cardN = $paramArray["CardN"];
        }
        if(array_key_exists("CardExpr",$paramArray)){
            $cardE = $paramArray["CardExpr"];
        }
        if(array_key_exists("SecCode",$paramArray)){
            $cardS = $paramArray["SecCode"];
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
    $conn = new mysqli(DataBaseLocation,DBUserName,DBPassword,NameOfDatabase);
    if ($conn->connect_error) { 
        die("Connection failed: " . $conn->connect_error); 
    }
     //is user a buyer?
    $buyer = false;
    $isBuyer = "SELECT User_ID
    FROM   buyer
    WHERE  User_ID = ? AND Card_Number IS NOT NULL AND Card_Expr IS NOT NULL AND Security_Code IS NOT NULL;";
    if($isBuyerStmt = $conn->prepare($isBuyer)){
        $isBuyerStmt->bind_param("i",$_POST["UserID"]);
        $isBuyerStmt->execute();
        $isBuyerStmt->store_result();
        if($isBuyerStmt->num_rows > 0){
            $buyer = true;
        }
    }

    $userInfo = ["UserID"=> $_POST["UserID"] , "UserN"=> $_POST["UserN"], "PassW"=> $_POST["PassW"], "Email"=> $_POST["Email"], "DOB"=> $_POST["DOB"]];
    $buyerInfo = ["UserID"=> $_POST["UserID"] , "CardN"=> $_POST["CardN"], "CardExpr"=> $_POST["CardExpr"], "SecCode"=> $_POST["SecCode"]];
    $result = "Success";
    //Make sure to do either edit/create buyer info
    $resultUser = editUserInfo($userInfo);
    if($buyer)
        $resultBuyer = editBuyerInfo($buyerInfo);
    else
        $resultBuyer = addBuyer($buyerInfo);
    if(str_contains("FAILURE",$resultUser)||str_contains("FAILURE",$resultBuyer)){
        $result = "Failed to change some value";
    }

    echo json_encode(["Result"=>$result],JSON_PRETTY_PRINT);
?>