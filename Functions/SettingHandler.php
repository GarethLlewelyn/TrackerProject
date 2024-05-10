<?php
require 'Connect.php'; //Loads DB connection
session_start();

//THIS FILE HANDLES ANY TRACKER SETTING CHANGE REQUESTS, FOUND IN THE SETTINGS TAB
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION["ID"])) {

    $SleepTimeValue = filter_var($_POST["SleepTimeValue"], FILTER_SANITIZE_NUMBER_INT);
    $ContactNumber = filter_var($_POST["ContactNumber"],FILTER_SANITIZE_STRING); //SMS Contact Number
    $AlertActivationTimeStart = $_POST["AlertActivationTimeStart"] . ":00"; //Time when Alert Starts
    $AlertActivationTimeEnd = $_POST["AlertActivationTimeEnd"]  . ":00"; //Time when Alert Ends
    $AlertActivationTimeCombined = $AlertActivationTimeStart . "-" . $AlertActivationTimeEnd;
    $AlertRadiusValue = filter_var($_POST["AlertRadiusValue"], FILTER_SANITIZE_NUMBER_INT); //Alert radius in meters
    $TrackerID = $_SESSION["TrackerID"];
        if(uploadToDatabase($SleepTimeValue,$ContactNumber,$AlertRadiusValue,$AlertActivationTimeCombined, $TrackerID, $client)){
            echo 1;
            $_SESSION["SleepTime"] = $SleepTimeValue;
            $_SESSION["ContactNumber"] = $ContactNumber;
            $_SESSION["AlertActivationTimeStart"] = $_POST["AlertActivationTimeStart"];
            $_SESSION["AlertActivationTimeEnd"] = $_POST["AlertActivationTimeEnd"];
            return true;
        }else{
            echo  0;
            return false;
        }
}
function uploadToDatabase($SleepTime,$ContactNumber, $AlertRadius, $AlertActivation, $ID, $client) {
    try{
        $result = $client->updateItem([
            'TableName' => 'Tracker_Pool',
            'Key' => [
                'Tracker_Pool_Table' => ['S' => $ID]
            ],
            "ReturnValues" => 'UPDATED_NEW',

            "UpdateExpression" => "SET #SleepTime = :SleepValue, #ContactNumber = :ContactValue, 
            #AlertRadius = :AlertRadiusValue, #AlertActivationTime = :AlertActivationTimeValue, #TrackerUpdaterSet = :TrackerUpdater",

            'ExpressionAttributeNames' => [
                '#SleepTime' => "SleepTime",
                '#ContactNumber' => "ContactNumber",
                '#AlertRadius' => "AlertRadius",
                '#AlertActivationTime' => "AlertActivationTime",
                '#TrackerUpdaterSet' => "TrackerUpdater"
            ],
            'ExpressionAttributeValues' => [
                ':SleepValue' => ['S' => $SleepTime],
                ':ContactValue' => ['S' => $ContactNumber],
                ':AlertRadiusValue' => ['N' => $AlertRadius],
                ':AlertActivationTimeValue' => ['S' => $AlertActivation],
                ':TrackerUpdater' => ['S' => "Y"]
            ]
        ]);
        return true;
    }catch(AwsException $e){
        echo "Update failed: " . $e->getMessage() . "\n";
        return false;
    }
}