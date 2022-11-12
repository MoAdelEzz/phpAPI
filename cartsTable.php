<?php

include 'customersTable.php';
include 'booksTable.php';

function CartExistBefore($conn,$cartID)
{
    $query = "SELECT * from carts WHERE id = '$cartID'";
    $Arr = mysqli_query($conn, $query);
    $cnt = 0;
    while ($row = mysqli_fetch_assoc($Arr)) {
        $cnt += 1;
    }
    return $cnt != 0;
}

function getCartInfo($conn,$userId)
{
    $user = getUserWithId($conn,"customers",$userId);
    
    $response = [["totalPrice"=>null,"elementsCnt" => null,"userFound" =>false]];
    
    if ($user == null)
    {
        $response[0]["userFound"] = false;
        $ret = json_encode($response);
        echo $ret;
        return $ret;
    }

    $query = "Select carts.total_price, carts.elements_cnt from customers
              inner join carts on customers.cart_id = carts.id and customers.username = '$userId'";
    $Arr = mysqli_query($conn,$query);
    
    $X = mysqli_fetch_assoc($Arr);
    $response[0]["totalPrice"] = $X['total_price'];
    $response[0]["elementsCnt"] = $X['elements_cnt'];
    $response[0]["userFound"] = true;
    $ret = json_encode($response);
    echo $ret;
    return $ret;
}

function getBooksInCart($conn,$cartID)
{
    $response =[['count' => 0]];
    if (CartExistBefore($conn,$cartID))
    {
        $query = "SELECT books.title,book_cart.book_cnt,books.id from book_cart inner join books on book_cart.bookID = books.id and book_cart.cartID = '$cartID'";
        $data =mysqli_query($conn,$query);
        while ($row = mysqli_fetch_assoc($data))
        {
            array_push($response,$row);
            $response[0]['count']++;
        }
    }
    $ret = json_encode($response);
    echo $ret;
    return $ret;
}

function deleteBookFromCart($conn,$cartID,$bookID)
{
    $query = "DELETE FROM book_cart where cartID = '$cartID' and bookID = '$bookID'";
    mysqli_query($conn,$query);
}

function BookInCartBefore($conn,$cartID ,$bookID)
{
    $query = "SELECT * from book_cart WHERE cartID = '$cartID' and bookID = '$bookID'";
    $Arr = mysqli_query($conn, $query);
    $cnt = 0;
    while ($row = mysqli_fetch_assoc($Arr)) {
        $cnt += 1;
    }
    return $cnt != 0;
}

function addToCart($conn,$cartID ,$bookID)
{
    $response = [['added' => false, 'noBook' => false, 'noCart' =>false, 'alreadyExist' => false]];
    if (!BookexistBefore($conn,$bookID))
    {
        $response[0]['noBook'] = true;
    }
    else if (!CartExistBefore($conn,$cartID))
    {
        $response[0]['noCart'] = true;
    }
    else if (BookInCartBefore($conn,$cartID ,$bookID))
    {
        $response[0]['alreadyExist'] = true;
    }
    else
    {
        $query = "INSERT INTO book_cart VALUES ('$cartID','$bookID',1)";
        mysqli_query($conn,$query);
        $response[0]['added'] = true;
    }

    $ret = json_encode($response);
    echo $ret;
    return $ret;
}

if (isset($_GET['userCart']))
{
    return getCartInfo($conn,$_GET['id']);
}

if (isset($_GET['addBook']))
{
    return addToCart($conn,$_GET['cartID'],$_GET['bookID']);
}

if (isset($_GET['getBooks']))
{
    return getBooksInCart($conn,$_GET['cartID']);
}

if (isset($_GET['deleteBook']))
{
    deleteBookFromCart($conn,$_GET['cartID'],$_GET['bookID']);
}

?>
