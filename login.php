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
        <input type = "submit" name ="login" value ="login">
    </form>
</body>
</html>

<?php
    #session_start();
    if (isset($_POST['login'])){
        if (!empty($_POST['USERNAME']) && !empty($_POST['password'])){
            $_SESSION['USERNAME'] = $_POST['USERNAME'];
            $_SESSION['password'] = $_POST['password'];
            include('database.php');
            $sql = "SELECT username,password_ FROM info_table";
            $result = mysqli_query($conn, $sql);
            

            if(mysqli_num_rows($result) > 0){ 
                while($row = mysqli_fetch_assoc($result)){
                    if ($_SESSION['USERNAME'] == $row['username'] && $_SESSION['password'] == $row['password_']){
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