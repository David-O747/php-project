<?php
$servername = "localhost";
$username = "root";  // Default for XAMPP
$password = "";  // Leave empty for XAMPP



$dbname = ""; // The database you created

// Create connection
$conn = mysqli_connect($servername, $username, $password,);

// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
} 
    

?>
