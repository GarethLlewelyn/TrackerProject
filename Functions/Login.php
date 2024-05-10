<?php

require 'Connect.php'; //Loads DB connection
session_start();


class Login
{

    private $Email;
    private $Password;
    private $client;
    public function __construct($Email, $Password){


        $this->Email = $Email;
        $this->Password = $Password;
        $this->client = $client;



    }




    function AuthenticateUser(){


        $result = $this->client->query([
            'TableName' => 'User',
            'IndexName' => 'Email-Index', // Use the new index name
            'KeyConditions' => [
                'Email' => [
                    'AttributeValueList' => [
                        ['S' => $this->Email]
                    ],
                    'ComparisonOperator' => 'EQ' // Equality comparison
                ]
            ]
        ]);

        if($result['Count'] === 1){

            if($result['Items'][0]['Password']['S'] == $this->Password){

                echo "Success";



                $_SESSION["ID"] = $result['Items'][0]['PrimaryKey']['S'];
                $_SESSION["Username"] = $result['Items'][0]['Name']['S'];
                $_SESSION["Email"] = $result['Items'][0]['Email']['S'];
                $_SESSION["TrackerID"] = $result['Items'][0]['TrackerID']['S'];
                $_SESSION["Logged_In"] = true;
                return 0;

            }else{
                echo "Password not match no no";
                return 1;
            }


        }else{ //Failed
            echo "Count is 0";
            return 2;
        }

    }


}