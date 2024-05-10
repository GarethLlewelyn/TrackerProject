<?php
require 'Connect.php'; //Loads DB connection
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TrackerID = $_POST["TrackerID"];
    $SessionTracker = $_SESSION["TrackerID"];
    if(TrackerUpdate($_SESSION["ID"], $TrackerID, $SessionTracker, $client)){
        echo 1;
        exit;
    }else{
        echo 0;
        exit;
    }
}
function TrackerUpdate($ID, $TrackerID, $SessionTracker, $client){ //Must add password verification
    if(CheckExists($TrackerID, $client) && $TrackerID != $SessionTracker){
        if(uploadToDatabase($TrackerID, $ID, $client)){
            $_SESSION['TrackerID'] = $TrackerID;
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

function CheckExists($TrackerID, $client){
    $result = $client->query([
        'TableName' => 'Tracker_Pool',
        'KeyConditions' => [
            'Tracker_Pool_Table' => [
                'AttributeValueList' => [
                    ['S' => $TrackerID]
                ],
                'ComparisonOperator' => 'EQ'
            ],
        ],
        'Select' => 'COUNT',
        'ConsistentRead' => true
    ]);
    if ($result['Count'] > 0) {
        return true;
    } else{
        return false;
    }
}

function uploadToDatabase($value, $ID, $client) {
    try{
        $result = $client->updateItem([
            'TableName' => 'User',
            'Key' => [
                'PrimaryKey' => ['S' => $ID]
            ],
            "ReturnValues" => 'UPDATED_NEW',
            "UpdateExpression" => "SET #field_name = :val",
            'ExpressionAttributeNames' => [
                '#field_name' => 'TrackerID'
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









