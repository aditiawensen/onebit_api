<?php
$servername = "localhost";
$username = "root";
$password = "ritter";
$dbname = "u875912243_bit";

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
} 
?>