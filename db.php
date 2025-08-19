<?php
$servername = "localhost";
$username = "root";   // replace with your DB username
$password = "";       // replace with your DB password
$dbname = "voir_db";  // replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
