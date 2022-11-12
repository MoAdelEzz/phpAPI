<?php

include "config.php";


function ValidateUser($conn,$table, $id, $password)
{
    $query = "SELECT * FROM $table 
                WHERE username = '$id'
            ";
    $x = mysqli_query($conn, $query);

    $temp = [];

    while ($row = mysqli_fetch_assoc($x)) {
        if ($row['password'] === $password) {
            array_push($temp, $row);
            return $temp;
        } else {
            return $temp;
        }
    }
}

function getUserWithId($conn,$table,$id)
{
    $query = "SELECT * FROM $table 
                WHERE username = '$id'
            ";
    $x = mysqli_query($conn, $query);
    $temp = [];

    while ($row = mysqli_fetch_assoc($x)) {
        array_push($temp, $row);
    }
    if (sizeof($temp) == 0)
    {
        return null;
    }
    else
    {
        return $temp[0];
    }
}

function existBefore($conn,$table, $id)
{
    $query = "SELECT * from $table WHERE username = '$id'";
    $Arr = mysqli_query($conn, $query);
    $cnt = 0;
    while ($row = mysqli_fetch_assoc($Arr)) {
        $cnt += 1;
    }
    return $cnt != 0;
}

function InsertNew($conn,$table,$username,$name,$password,$email)
{
    $x = existBefore($conn,$table,$username);

    if ($x == true)
    {
        return false;
    }


    if ($table == "admins")
    {
        $query = "INSERT INTO admins VALUES ('$username','$name','$password','$email')";
        $x = mysqli_query($conn,$query);
        return true;
    }
    
    if ($table == 'customers')
    {
    $query = "INSERT INTO carts VALUES (NULL,0,0)";
    mysqli_query($conn,$query);

    $query = "SELECT * FROM carts ORDER BY id DESC ";
    $y = mysqli_query($conn,$query);
    $newCart = mysqli_fetch_assoc($y)['id'];


    $query = "INSERT INTO customers VALUES ('$username','$name','$password','$email','$newCart')";
    $x = mysqli_query($conn,$query);
    return true;
    }
}

if (isset($_GET["op"])) {
    if ($_GET["op"] == '1') {
        $x = ValidateUser($conn,$_GET['table'], $_GET["username"], $_GET["password"]);
        $y = json_encode($x);
        echo $y;
        return json_encode($x);
    }

    if ($_GET["op"] == '2') // Check if the username already existe
    {
        $x = existBefore($conn,$_GET['table'], $_GET['id']);
        $response = [];
        array_push($response, ['Exist' => $x]);
        echo json_encode($response);
        return json_encode($response);
    }

    if ($_GET["op"] == '3') // insert new user
    {
        $x = InsertNew($conn,$_GET['table'],$_GET['username'],$_GET['name'],$_GET['password'],$_GET['email']);
        $response = json_encode([["Inserted" => $x]]);
        echo $response;
        return $response;
    }
}

