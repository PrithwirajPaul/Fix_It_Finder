<?php
session_start();

if(isset($_FILES['profile_picture'])) {
    
    if($_FILES["profile_picture"]["error"] === 4) {
        echo "<script>alert('No picture was uploaded!');</script>";
        exit;
    } else {
        $thumbnail = $_FILES['profile_picture']['name'];
        $tmpname = $_FILES['profile_picture']['tmp_name'];

        $validImageExtension = ['jpg', 'jpeg', 'png', 'webp'];
        $imgExtension = strtolower(pathinfo($thumbnail, PATHINFO_EXTENSION));

        if(!in_array($imgExtension, $validImageExtension)) {
            echo "<script>alert('Invalid file format! Only JPG, JPEG, PNG, and WEBP are allowed.');</script>";
            exit;
        } else {
            $newImgname = uniqid('img_', true) . '.' . $imgExtension;
            $newImgnamePath = 'images/' . $newImgname;

            if(move_uploaded_file($tmpname, $newImgnamePath)) {
                
                include('database.php');

                $updateSql = "UPDATE info_table SET img = '$newImgnamePath' WHERE userid = '{$_GET['user']}'";
                
                if(mysqli_query($conn, $updateSql)) {
                    $_SESSION['image'] = $newImgnamePath;
                    header('Location: profile.php?user=' . $_GET['user']);
                    exit;
                } else {
                    echo "<script>alert('Database update failed!');</script>";
                }

                mysqli_close($conn);

            } else {
                echo "<script>alert('File upload failed!');</script>";
            }
        }
    }
} else {
    echo "<script>alert('No file uploaded!');</script>";
}
?>
