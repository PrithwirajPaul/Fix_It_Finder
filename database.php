<?php
try{
    $conn = mysqli_connect("localhost","root","","social_database");
}
catch(Exception $e){
    echo 'failed to create';
}
?>