<!-- manually create your user (name,pass) in this page on 'info_table' -->
<?php
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "social_database";

     // Create connection
     $conn = new mysqli($servername, $username, $password);
 
     // Check connection
    if ($conn->connect_error) {
        die("DB Connection failed: " . $conn->connect_error);
    } 
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error creating database: " . $conn->error;
    }

    // Close connection to create a new one for the database
    $conn->close();

    // Create connection to the new database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE TABLE IF NOT EXISTS post_manage (
        username VARCHAR(255),
        post_content TEXT,
        post_title VARCHAR(255),
        vote INT,
        post_id INT PRIMARY KEY AUTO_INCREMENT
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }


    $sql = "CREATE TABLE IF NOT EXISTS post_status (
        post_id INT,
        status_ INT,
        username VARCHAR(255),
        id INT AUTO_INCREMENT
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $sql = "CREATE TABLE IF NOT EXISTS info_table (
        username VARCHAR(255) PRIMARY KEY,
        password_ TEXT,
        type_ VARCHAR(255)
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }


    $sql = "CREATE TABLE IF NOT EXISTS connected_pairs (
        professional VARCHAR(255),
        average_joe VARCHAR(255),
        count INT PRIMARY KEY AUTO_INCREMENT
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }

?>