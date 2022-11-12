<?php

$host = "localhost";
$dbName = "bookshop";
$username = "root";
$password = "";

$conn = mysqli_connect($host,$username,$password,$dbName);

header('Header-Type: application/json');
header('Access-Control-Allow-Origin: *');


?>