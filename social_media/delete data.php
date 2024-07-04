<?php
# Deletets post from the database
    include_once("database.php");
    $sql = "DELETE FROM post_manage WHERE post_id = '" . $_GET['num'] . "'";
    mysqli_query($conn, $sql); 
    $sql = "DELETE FROM post_status WHERE post_id = '" . $_GET['num'] . "'";
    mysqli_query($conn, $sql); 
    mysqli_close($conn);
    header("location: home.php");
?>