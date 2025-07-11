<?php
$conn = mysqli_connect('localhost', 'root', '', 'register');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
