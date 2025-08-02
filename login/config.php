<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "register";

$conn =  new mysqli(hostname: $host, username: $user, password: $password, database: $database);

if ($conn->connect_error) {
    die("connection failed: ". $conn->connect_error);
}

?>