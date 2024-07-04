<!-- This is like a notification showing page after both user and professional have agreed on service -->
<h2>HELLO WORK</h2>
<?php
    include('database.php');
    $isAccepted =  $_GET['op'];
    $acceptor =  $_GET['acceptor'];
    $postId =  $_GET['postId'];
    $current_user = $_GET['cuser'];

    echo $acceptor;
    echo $postId;

    if($isAccepted){
        $sql = "DELETE FROM post_manage WHERE post_id = '$postId' AND username = '$current_user'";
        mysqli_query($conn, $sql); 
        echo '<script>alert("You have created a connection")</script>';
        $sql = "INSERT INTO connected_pairs(professional, average_joe) VALUES('$acceptor','$current_user')";
        mysqli_query($conn, $sql);
    }

    $sql = "DELETE FROM post_status WHERE username = '$acceptor' AND post_id = '$postId'";
    mysqli_query($conn, $sql); 
    mysqli_close($conn);
    
    echo $current_user;
    header("location:view-accepted.php?cuser=".$current_user);
?>