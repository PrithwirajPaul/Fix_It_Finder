<?php
session_start();

$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);


if(isset($_POST['signup'])) {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['type'])){
        $_SESSION['USERNAME'] = $_POST['username'];
        $_SESSION['type'] = $_POST['type'];
        # taking a user type input, like 'professional' & 'averageguy'. Used Y and X to define. 
        include('database.php');
        $sql = "Insert into info_table (username,password_,type_,email,phone,adrs,zip) VALUES('{$_POST['username']}','{$hashed_password}','{$_POST['type']}','{$_POST['email']}','{$_POST['phone']}','{$_POST['address']}','{$_POST['zip']}')";

        if(mysqli_query($conn, $sql)){ 
            $result = mysqli_query($conn, "select * from info_table where username='{$_POST['username']}' && type_='{$_POST['type']}' && email='{$_POST['email']}'");
            $row= mysqli_fetch_assoc($result);
            $_SESSION['username'] = $row['username'];
            $_SESSION['userid'] = $row['userid'];
            $_SESSION['type'] = $_POST['type'];
            $_SESSION['email']= $row['email'];
            $_SESSION['phone']=$row['phone'];
            $_SESSION['address']= $row['adrs'];
            $_SESSION['zip']=$row['zip'];
            $_SESSION['image']=$row['img'];
            header('location: home.php');
        }else{
            echo 'Error occured while registering try again!';
        }
        mysqli_close( $conn );   
        
    }
    else{
        echo 'Missing username/password';
    }
}


echo '<style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
        }
        .message {
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
      </style>';
echo '<div class="message"><h1>Registration successful!</h1><p>Welcome, ' . htmlspecialchars($username) . '.</p></div>';
?>