<?php
session_start();
header('Content-Type: application/json');
if(!isset($_SESSION["TrackerID"])){
    exit;
}

$TrackerKey = ["lat" => $_SESSION["Lat"], "lng" =>$_SESSION["Lng"], "TimeStamp" => $_SESSION["TimeStamp"],
    "SatQuality" => $_SESSION["SatQuality"], "Battery" => $_SESSION["Battery"]];

echo  json_encode($TrackerKey); //Returns session data relating to current tracker details
exit;
?>


