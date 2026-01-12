<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "klanten";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database verbinden mislukt" . mysqli_connect_error());
}
