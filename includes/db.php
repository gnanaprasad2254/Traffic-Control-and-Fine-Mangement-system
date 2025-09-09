<?php
session_start();
$host = "localhost";
$database = "DBMS";
$dbusername = "root";
$dbpassword = "";
$conn = mysqli_connect($host, $dbusername, $dbpassword, $database);
if (!$conn)
    die("Connection Failed" . mysqli_connect_error());
?>