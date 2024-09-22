<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "social_database";

     // Create connection
     $conn = new mysqli($servername, $username, $password);
 
    if ($conn->connect_error) {
        die("DB Connection failed: " . $conn->connect_error);
    } 
    
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sql) === TRUE) {
    } else {
        echo "Error creating database: " . $conn->error;
    }

    $conn->close();

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE TABLE IF NOT EXISTS post_manage (
        userid int,
        post_content TEXT,
        post_title VARCHAR(255),
        vote INT,
        post_id INT PRIMARY KEY AUTO_INCREMENT,
        isPublic INT,
        photo VARCHAR(255)
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }


    $sql = "CREATE TABLE IF NOT EXISTS post_status (
        post_id INT,
        status_ INT,
        userid int,
        id INT  AUTO_INCREMENT,
        primary key (id)
        
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $sql = "CREATE TABLE IF NOT EXISTS info_table (
        userid int primary key auto_increment,
        username VARCHAR(255),
        password_ TEXT,
        type_ VARCHAR(255),
        email VARCHAR(255),
        phone VARCHAR(255),
        adrs VARCHAR(255),
        zip VARCHAR(255),
        bio text,
        exp int,
        img VARCHAR(255) default ('images/default_pp.jpg')
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }


    $sql = "CREATE TABLE IF NOT EXISTS connected_pairs (
        professional int,
        average_joe int,
        postID INT PRIMARY KEY
    )";

    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $sql = "create table if not exists interaction (
        postID int,
        userID int,
        type varchar(100)
    )";
    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }

    $sql = "create table if not exists postWork (
        postID int,
        review int,
        comments varchar(1000)
    )";
    if ($conn->query($sql) === TRUE) {
        
    } else {
        echo "Error creating table: " . $conn->error;
    }
?>
