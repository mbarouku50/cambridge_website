<?php
$conn=mysqli_connect("localhost","phpmyadmin","m","CambridgeDB");
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>