<?php

$server = "localhost";
$username = "root";
$password = "";
$database = "qr_self";

$con = mysqli_connect($server, $username, $password, $database);
mysqli_set_charset($con, 'utf8mb4');

if(!$con){
    die('Connection Failed' . mysqli_connect_error());
}

?>