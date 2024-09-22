<!-- This page handles accept/decline request. if accepted then establishes connection by inserting data to post_status table -->
<?php
session_start();
include_once("database.php");
$acceptor = $_SESSION['userid'];
$sql = "SELECT * FROM post_status Where  post_id = '" . $_GET['pid'] . "' and userid='$acceptor'";
$result = $conn->query($sql);
$hereToDecline = False;
if ($row=mysqli_fetch_assoc($result)) {
    if ($row['status_'] == 1) {
        $hereToDecline = True;
    } 
}
if ($hereToDecline) {
    $sql = "DELETE FROM post_status WHERE userid = '$acceptor' AND post_id = '" . $_GET['pid'] . "'";
    mysqli_query($conn, $sql);
} else {
        $temp = $_GET['pid'];
        $sql = "INSERT INTO post_status(post_id,status_,userid) VALUES('$temp',1,'$acceptor')";
        mysqli_query($conn, $sql);
}


mysqli_close($conn);
if(isset($_GET['cuser'])){
    header('location:profile.php?user='.$_GET['cuser']);
}else{
    header("location:home.php");
}
?>