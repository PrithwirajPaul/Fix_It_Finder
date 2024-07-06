<?php
    session_start();
    if (isset($_POST['login'])){
        if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['type'])){
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            echo $_POST['username'].' '. $_POST['password']. ' '. $_POST['type']. ' '. $hashed_password ;
            # taking a user type input, like 'professional' & 'averageguy'. Used Y and X to define. 
            include('database.php');
            $sql = "SELECT * FROM info_table where type_='{$_POST['type']}' && username='{$_POST['username']}'";
            $result = mysqli_query($conn, $sql);
            

            if(mysqli_num_rows($result) > 0){ 
                $row = mysqli_fetch_assoc($result);
                if(password_verify($_POST['password'],$row['password_'])){
                    $_SESSION['username']=$row['username'];
                    $_SESSION['type']=$row['type_'];
                    $_SESSION['userid'] = $row['userid'];
                    $_SESSION['email']= $row['email'];
                    $_SESSION['phone']=$row['phone'];
                    $_SESSION['address']= $row['adrs'];
                    $_SESSION['zip']=$row['zip'];
                    $_SESSION['image']=$row['img'];
                    header('location: home.php');
                }else{
                    //show message 'invalid password with the username'
                }
            }else {
                echo 'Connection lost';
            mysqli_close( $conn ); 
            }
        }
        else{
            echo 'Missing username/password';
        }
    }