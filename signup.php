<?php
session_start();

$password= $_POST['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


if(isset($_POST['signup'])) {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['type'])){
        $_SESSION['USERNAME'] = $_POST['USERNAME'];
        $_SESSION['type'] = $_POST['type'];
        # taking a user type input, like 'professional' & 'averageguy'. Used Y and X to define. 
        include('database.php');
        $sql = "Insert into info_table (username,password_,type_,email,phone,adrs,zip) VALUES('{$_POST['username']}','{$_POST['password']}','{$_POST['type']}','{$_POST['email']}','{$_POST['phone']}','{$_POST['address']}','{$_POST['zip']}'";
        

        if(mysqli_query($conn, $sql)){ 
            while($row = mysqli_fetch_assoc($result)){
                if ($_SESSION['USERNAME'] == $row['username'] && $_SESSION['password'] == $row['password_']&& $_SESSION['type'] == $row['type_']){
                    header('location: home.php');
                }
            } 
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