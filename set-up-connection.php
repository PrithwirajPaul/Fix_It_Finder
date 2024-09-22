<!-- This is like a notification showing page after both user and professional have agreed on service -->

<?php
    include('database.php');
    $isAccepted =  $_GET['op'];
    $acceptor =  $_GET['acceptor'];
    $postId =  $_GET['postId'];
    $current_user = $_GET['cuser'];
    if($isAccepted){
        $Fsql = "UPDATE post_status SET status_=2 WHERE post_id ='$postId' and userid='$acceptor'";
        mysqli_query($conn, $Fsql); 
        $Lsql = "UPDATE post_status SET status_=3 WHERE post_id ='$postId' and status_=1";
        mysqli_query($conn, $Lsql); 
        echo '<script>alert("You have created a connection")</script>';
        $sql = "INSERT INTO connected_pairs(professional, average_joe, postID) VALUES('$acceptor','$$current_user','$postId')";
        mysqli_query($conn, $sql);
    }else{
        $sql = "update post_status set status_=3 WHERE post_id = '$postId' and userid='$acceptor'";
        mysqli_query($conn, $sql); 
    }

    mysqli_close($conn);
    header("location:view-accepted.php?cuser=".$current_user);
?>