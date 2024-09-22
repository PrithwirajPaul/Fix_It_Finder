<?php 
include('database.php');
$post_id= $_GET['pid'];

$sql= "update post_status set status_=4 where post_id='$post_id'";
$res= mysqli_query($conn,$sql);
header("location:view-accepted.php");
?>