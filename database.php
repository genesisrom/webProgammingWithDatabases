<?php
    // connect to MySQL 
    $hn = "localhost";
    $un = "mcuser";
    $pw = "Pa55word";
    $conn = new mysqli($hn, $un, $pw);
    if ($conn->connect_error) die("Fatal Error");

    // create the database if it not yet exists
    $query = "CREATE DATABASE IF NOT EXISTS volunteerEvent";
    $result = $conn->query($query);
    if (!$result) die("Fatal Error1");

    // use the volunteerEvent database 
    $query = "USE volunteerEvent";
    $result = $conn->query($query);
    if (!$result) die("Fatal Error2");
 
    // create the volunteers table if it not yet exists 
    $query = "CREATE TABLE IF NOT EXISTS volunteerInfo (
                volunteerID INT NOT NULL AUTO_INCREMENT, 
                firstName VARCHAR(75),
                lastName VARCHAR(75),
                email VARCHAR(100),
                PRIMARY KEY (volunteerID))";
    $result = $conn->query($query);        
    if (!$result) die("Fatal Error3");

    // create the takenShifts table if it not yet exists
    $query = "CREATE TABLE IF NOT EXISTS takenShifts (
                shiftID INT NOT NULL AUTO_INCREMENT, 
                volunteerID INT NOT NULL,
                shiftTime VARCHAR(20),
                PRIMARY KEY (shiftID),
                FOREIGN KEY (volunteerID) REFERENCES volunteerInfo(volunteerID))";
    $result = $conn->query($query);
    if (!$result) die("Fatal Error4");
?>