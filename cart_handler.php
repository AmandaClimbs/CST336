<?php
session_start();
if (!isset($_SESSION['cart']) || $_POST['emptyCart'] == 1) {
      $_SESSION['cart'] = array();
    }
$cartAdd = $_POST['itemToCart'];
$_SESSION['cart'] = array_merge($_SESSION['cart'], $cartAdd);


header('Location:index.php');

?>
