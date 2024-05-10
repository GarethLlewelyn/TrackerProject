<?php
require 'Connect.php'; //Loads DB connection
use \Ramsey\Uuid\Uuid; //Uses the Ramsey UUID Library
session_start();
if($_SESSION) {
    header('Location: index.php');
}
$user = strtolower(filter_var($_POST["Name"], FILTER_SANITIZE_SPECIAL_CHARS));
$Email = strtolower(filter_var($_POST["Email"], FILTER_SANITIZE_EMAIL));
$pass = filter_var($_POST["Password"], FILTER_SANITIZE_SPECIAL_CHARS);
$uuid = Uuid::uuid4()->toString(); // Generates a version 4 UUID
header('Content-Type: application/json');
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tableName = 'User';
    $result = $client->query([ // Change from 'getItem' to 'query'
        'TableName' => $tableName,
        'IndexName' => 'Email-Index', // Use the new index name
        'KeyConditions' => [
            'Email' => [
                'AttributeValueList' => [
                    ['S' => $Email]
                ],
                'ComparisonOperator' => 'EQ' // Equality comparison
            ]
        ]
    ]);
    if ($result['Count'] == 0) {
        try {
            $result = $client->putItem([
                'TableName' => $tableName,
                'Item' => [
                    'PrimaryKey' => ['S' => $uuid],
                    'Email' => ['S' => $Email],  // 'N' for number
                    'Name' => ['S' => $user],
                    'Password' => ['S' => $pass],
                    'TrackerID' => ['S' => ""]
                ]
            ]);
            echo "1";
            exit;
        } catch (DynamoDbException $e) {
            echo "Unable to add item:\n";
            echo $e->getMessage() . "\n";
            echo "2";
            exit;
        }
    } else {
        echo "3";
        exit;
    }

}

?>