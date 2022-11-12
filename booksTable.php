<?php

use LDAP\Result;

include "config.php";



function BookexistBefore($conn, $id)
{
    $query = "SELECT * from books WHERE id = '$id'";
    $Arr = mysqli_query($conn, $query);
    $cnt = 0;
    while ($row = mysqli_fetch_assoc($Arr)) {
        $cnt += 1;
    }
    return $cnt != 0;
}



function addNewBook($conn,$id,$title,$body,$Iurl)
{
    $query = "INSERT INTO books values ('$id','$title','$body','$Iurl')";
    $Arr = mysqli_query($conn, $query);
}

function deleteBook($conn,$id)
{
    $query = "DELETE FROM books WHERE  id ='$id'";
    $Arr = mysqli_query($conn, $query);
}

function getAllBooks($conn)
{
    $query = "SELECT * FROM books";

    $x = mysqli_query($conn, $query);

    $Result = [];

    while ($row = mysqli_fetch_assoc($x)) {
        $Book = ['bookID' => $row['id'], 'bookTitle' => $row['title'], 'bookBody' => $row['body'], 'ImageUrl' => $row['image_url']];
        array_push($Result, $Book);
    }

    return json_encode($Result);
}

if (isset($_GET['AB'])) {
    $x = getAllBooks($conn);
    echo $x;
    return $x;
}

if (isset($_GET['Add'])) {
    $exist = true;
    if (!BookExistBefore($conn,$_GET['id']))
    {
        $exist = false;

        $X = $_GET['Iurl'];
        for ($i = 0 ; $i < strlen($X); $i++)
        {
            if ($X[$i] == '$')
            {
                $X[$i] = '&';
            }
        }
        addNewBook($conn,$_GET['id'],$_GET['title'],$_GET['body'],$X);
    }

    $response = [['Added' => !$exist, 'Exist' => $exist]];
    echo json_encode($response);
    return json_encode($response);
}

if (isset($_GET['Delete']))
{
    $response = [["deleted"=>false,"Exist" => true]];
    if (BookexistBefore($conn,$_GET['bookID']))
    {
        $response[0]["Exist"] = true;
        deleteBook($conn,$_GET['bookID']);
        $response[0]["deleted"] = true;
    }

    $ret = json_encode($response);
    echo $ret;
    return $ret;
}


return getAllBooks($conn);
