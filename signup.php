<?php
session_start();

$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

if (isset($_POST['signup'])) {
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['type'])) {
        include('database.php');
        
        // Sanitize email and type input
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        
        // Check if the email and type combination already exists
        $email_check_sql = "SELECT * FROM info_table WHERE email = '{$email}' AND type_ = '{$type}'";
        $email_check_result = mysqli_query($conn, $email_check_sql);

        if (mysqli_num_rows($email_check_result) > 0) {
            // If the combination already exists, show error message
            echo '<div class="message"><h1>Email already registered for this role!</h1><p>Please use a different email or role.</p></div>';
        } else {
            // If the email and role combination doesn't exist, proceed with registration
            

            // Insert new user
            if($type=='service-provider') {
                $sql = "INSERT INTO info_table (username, password_, type_, email, phone, adrs, zip,experience) 
                    VALUES ('{$_POST['username']}', '{$hashed_password}', '{$type}', '{$email}', '{$_POST['phone']}', '{$_POST['address']}', '{$_POST['zip']}','{$_POST['experience_years']}')";
            }else{
                $sql = "INSERT INTO info_table (username, password_, type_, email, phone, adrs, zip) 
                    VALUES ('{$_POST['username']}', '{$hashed_password}', '{$type}', '{$email}', '{$_POST['phone']}', '{$_POST['address']}', '{$_POST['zip']}')";
            }
            

            if (mysqli_query($conn, $sql)) {
                $result = mysqli_query($conn, "SELECT * FROM info_table WHERE username='{$_POST['username']}' && type_='{$type}' && email='{$email}'");
                $row = mysqli_fetch_assoc($result);
                
                // Set session variables
                $_SESSION['username'] = $row['username'];
                $_SESSION['userid'] = $row['userid'];
                $_SESSION['type'] = $type;
                $_SESSION['email'] = $row['email'];
                $_SESSION['phone'] = $row['phone'];
                $_SESSION['address'] = $row['adrs'];
                $_SESSION['zip'] = $row['zip'];
                $_SESSION['image'] = $row['img'];
                if($type=='service-provider') $_SESSION['exp']=$row['experience'];

                // Redirect to home page
                header('location: home.php');
            } else {
                echo 'Error occurred while registering, please try again!';
            }
        }

        mysqli_close($conn);
    } else {
        echo 'Missing required fields!';
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
?>
