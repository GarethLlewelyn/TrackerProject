<?php
use Aws\DynamoDb\Marshaler;
require 'Connect.php'; //Loads DB connection
session_start();
$result = $client->getItem([
    'TableName' => 'Tracker_Pool',
    'Key' => [
        'Tracker_Pool_Table' => ['S' => $_SESSION["TrackerID"]]
    ]
]);

$marshaler = new Marshaler();
$data = $marshaler->unmarshalItem($result['Item']);
if (isset($data['coordinate_history'])) {

    $_SESSION["SleepTime"] = $data["SleepTime"];
    $_SESSION["ContactNumber"] = $data["ContactNumber"];
    $AlertActivationSplit = explode('-', $data["AlertActivationTime"]);
    $_SESSION["AlertActivationTimeStart"] =  substr($AlertActivationSplit[0], 0,-3);
    $_SESSION["AlertActivationTimeEnd"] = substr($AlertActivationSplit[1], 0,-3);
    $_SESSION["Lat"] = $data["Lat"]; //Set Session info for tracker / Saves provision from AWS if saved now
    $_SESSION["Lng"] = $data["Lng"];
    if($data["GpsCount"] <=3){
        $_SESSION["SatQuality"] = "Bad";
    }elseif($data["GpsCount"] > 3 && $data["GpsCount"] <=6){
        $_SESSION["SatQuality"] = "Okay";
    }elseif($data["GpsCount"] > 6){
        $_SESSION["SatQuality"] = "Great";
    }
    $cleanTimestamp = DateTime::createFromFormat('Y-m-d-H-i-s', $data["RecordTimestamp"]);
    $interval = $cleanTimestamp->diff(new DateTime());
    $totalMinutes = ($interval->days * 24 * 60) + ($interval->h * 60) + $interval->i;// Calculate total minutes since RecordTimestamp
    $hours = floor($totalMinutes / 60); //Extract hours and minutes
    $minutes = $totalMinutes % 60;
    $_SESSION["TimeStamp"] = $hours . " hours, " . $minutes . " minutes";// Store the result
    $_SESSION["Battery"] = $data["Battery"];



    $ReversedCords = array_reverse($data['coordinate_history']);
    $columnList = array_slice($ReversedCords, 0, 300, true); //Split returned list into 300 records
    foreach ($columnList as $coordString) { //Converts the location from strings into usable key arrays.
        $cleanString = trim($coordString, "() ");  // Remove parentheses and spaces

        if ($cleanString === "Invalid, Invalid") {
            continue; // Skip to the next iteration of the loop
        }


        list($lngStr, $latStr) = explode(",", $cleanString);

        $locations[] = [
            'lat' => floatval($latStr),
            'lng' => floatval($lngStr)
        ];
    }

    $locations = json_encode($locations);
    header('Content-Type: application/json');
    echo $locations;


}





