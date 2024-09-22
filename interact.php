<?php
session_start();
include('database.php');

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo "<script>alert('Please log in first to subscribe a channel')</script>";
    header('refresh:1; url=home.php');
}

// Get user_id and post_id
$user_id = $_SESSION['userid'];
$post_id = $_POST['post_id'];
$action = $_POST['action'];

// Check if the user is already interacted to the post
$checkSaveQuery = "SELECT * FROM interaction WHERE userID = '$user_id' AND postID = '$post_id' AND type = '{$action}'";
$checkSaveResult = mysqli_query($conn, $checkSaveQuery);

// If the user is already saved the post, unsave the action
if (mysqli_num_rows($checkSaveResult) > 0) {
    $unSaveQuery = "DELETE FROM interaction WHERE userID = '$user_id' AND postID = '$post_id' AND type = '{$action}'";
    if (mysqli_query($conn, $unSaveQuery)) {
        // header('refresh:1; url=home.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If the user is not saved, save the action
    $saveQuery = "INSERT INTO interaction (userID, postID, type) VALUES ('$user_id', '$post_id', '{$action}')";
    if (mysqli_query($conn, $saveQuery)) {
        // header('refresh:1; url=home.php');
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>