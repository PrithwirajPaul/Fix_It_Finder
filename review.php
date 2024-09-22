<?php 
    include('database.php');
    if(isset($_POST['Add']) || isset($_POST['Remove'])) {

        if(isset($_POST['Add'])) {
            $rating=0;
            $sql="Insert into postWork(postID,review,comments) values('{$_GET['pID']}','{$_POST['ratings']}','{$_POST['comment']}')";
            mysqli_query($conn,$sql);
            
         }else {
            $sql="Insert into postWork(postID,review,comments) values('{$_GET['pID']}','0','null')";
            mysqli_query($conn,$sql);
         }
         $sql="update post_status set status_=5 where post_id='{$_GET['pID']}'";
         mysqli_query($conn,$sql);
    }
    header('location:view-accepted.php');
  ?>