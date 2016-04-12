<?php

function getDatabaseConnection() {
    
    $host = getenv('IP');
    $dbname = "shoppingDB";
    $username = "web_user";
    $password = "";

    //creates new connection
    $dbConn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Setting Errorhandling to Exception
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    
    return $dbConn;
    
}

?>