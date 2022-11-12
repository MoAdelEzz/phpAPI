<?php

$host = "sql8.freesqldatabase.com";
$dbName = "sql8563148";
$username = "sql8563148";
$password = "YvHEPGkcRj";

$conn = mysqli_connect($host,$username,$password,$dbName);

header('Header-Type: application/json');
header('Access-Control-Allow-Origin: *');

echo "DOne";

?>