<!-- This page handles accept/decline request. if accepted then establishes connection by inserting data to post_status table -->
<?php
    include_once("database.php");
    $sql = "SELECT * FROM post_status Where  post_id = '" . $_GET['pid'] . "'";
    $result = $conn->query($sql); 
    $status=0;
    $acceptor = $_GET['acceptor'];
  
        $hereToDecline = False;
        if(mysqli_num_rows($result) > 0){ 
            while($row = mysqli_fetch_assoc($result)){
                if ($row['status_'] == 1 && $row['username'] == $acceptor){
                    $hereToDecline=True;
                    $status=1;
                }
            } 
        }
       
    $status+=1;
    $status = $status % 2;
    
    $nullName='';
    if ($hereToDecline){
        $sql = "DELETE FROM post_status WHERE username = '$acceptor' AND post_id = '" . $_GET['pid'] . "'";
        mysqli_query($conn, $sql); 
    }
    else{
            $temp = $_GET['pid'];
            $sql = "INSERT INTO post_status(post_id,status_,username) VALUES('$temp',1,'$acceptor')";
            mysqli_query($conn, $sql); 
        }


    mysqli_close($conn);
    header("location:home.php");
?>