<?php
require 'Connect.php'; //Loads DB connection
session_start();

$Email = filter_var($_POST["Email"],FILTER_SANITIZE_EMAIL);
$Password = filter_var($_POST["Password"],FILTER_SANITIZE_SPECIAL_CHARS);
$tableName = 'User';
AuthenticateUser($Email, $Password, $client); //Include $client
function AuthenticateUser($EmailAuth, $Password, $client){

    $result = $client->query([
        'TableName' => 'User',
        'IndexName' => 'Email-Index', // Use the new index name
        'KeyConditions' => [
            'Email' => [
                'AttributeValueList' => [
                    ['S' => $EmailAuth]
                ],
                'ComparisonOperator' => 'EQ' // Equality comparison
            ]
        ]
    ]);
    if($result['Count'] === 1){
        if($result['Items'][0]['Password']['S'] == $Password){
            $_SESSION["ID"] = $result['Items'][0]['PrimaryKey']['S'];
            $_SESSION["Username"] = $result['Items'][0]['Name']['S'];
            $_SESSION["Email"] = $result['Items'][0]['Email']['S'];
            $_SESSION["TrackerID"] = $result['Items'][0]['TrackerID']['S'];
            echo "1";
            exit;
        }else{
            echo "2";
            exit;
        }
    }else{ //Failed
        echo "3";
        exit;
    }

}









