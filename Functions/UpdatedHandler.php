<?php
require 'Connect.php'; //Loads DB connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $UserFullName = $_POST["UserFullName"];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    if(AccountDetailsUpdate($_SESSION["ID"], $UserFullName, $Email, $Password, $client)){
        echo 1;
        exit;
    }else{
        echo 0;
        exit;
    }
}
function AccountDetailsUpdate($ID, $Name, $Email, $Password, $client){

    $result = $client->query([ //Retrieve password for verification
        'TableName' => 'User',
        'KeyConditionExpression' => '#id = :Primary',
        'ExpressionAttributeNames' => [
            '#id' => 'PrimaryKey'
        ],
        'ExpressionAttributeValues' => [
            ":Primary" => ['S' => $ID]
        ]
    ]);


    if($result['Items'][0]['Password']['S'] == $Password) {
        $TrueCount = 0;
        if ($_SESSION['Username'] !== $Name) {
            error_log("Update attempted: ID = $ID, value = $Name");

            if (uploadToDatabase('Name', $Name, $ID, $client)) {
                //echo "User DB";
                $_SESSION['Username'] = $Name;
                $TrueCount++;
            }
        }

        if ($_SESSION['Email'] !== $Email) {
            if (uploadToDatabase('Email', $Email, $ID, $client)) {
                //echo "Email DB";
                $_SESSION['Email'] = $Email;
                $TrueCount++;
            }
        }
        if ($TrueCount >= 1) {
            return true;
        } else {
            return false;
        }
    }else{
        return false;
    }

}

function uploadToDatabase($field, $value, $ID, $client) {
    try{
        $result = $client->updateItem([
            'TableName' => 'User',
            'Key' => [
                'PrimaryKey' => ['S' => $ID]
            ],
            "ReturnValues" => 'UPDATED_NEW',
            "UpdateExpression" => "SET #field_name = :val",
            'ExpressionAttributeNames' => [
                '#field_name' => $field
            ],
            'ExpressionAttributeValues' => [
                ':val' => ['S' => $value]
            ]
        ]);
    return true;
    }catch(AwsException $e){
        echo "Update failed: " . $e->getMessage() . "\n";
        return false;

    }
}











