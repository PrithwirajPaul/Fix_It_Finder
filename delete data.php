<?php
    include_once("database.php");
    $sql = "DELETE FROM post_manage WHERE post_id = '" . $_GET['num'] . "'";
    mysqli_query($conn, $sql); 
    mysqli_close($conn);
    header("location: home.php");
?>