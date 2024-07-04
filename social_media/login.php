<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="login.php" method="post">
        username:<br>
        <input type ="text" name = "USERNAME"><br>
        password:<br>
        <input type ="text" name = "password"><br>
        type:<br>
        <input type ="text" name = "type"><br>
        <input type = "submit" name ="login" value ="login">
    </form>
</body>
</html>

<?php
    #session_start();
    if (isset($_POST['login'])){
        if (!empty($_POST['USERNAME']) && !empty($_POST['password']) && !empty($_POST['type'])){
            $_SESSION['USERNAME'] = $_POST['USERNAME'];
            $_SESSION['password'] = $_POST['password'];
            $_SESSION['type'] = $_POST['type'];
            # taking a user type input, like 'professional' & 'averageguy'. Used Y and X to define. 
            include('database.php');
            $sql = "SELECT username,password_,type_ FROM info_table";
            $result = mysqli_query($conn, $sql);
            

            if(mysqli_num_rows($result) > 0){ 
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