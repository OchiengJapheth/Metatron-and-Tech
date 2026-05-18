<?php
$conn = mysqli_connect("localhost", "root", "", "socialapp");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>