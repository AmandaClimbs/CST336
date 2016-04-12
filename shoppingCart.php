<?php
   session_start(); 
   if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
   }
    include('includes/database.php');
    global $dbConnection;

    $dbConnection = getDatabaseConnection();
    $query = "SELECT productName, price, calories, productTypeId, productId FROM Product ";
    $where_str = '';
    $time = 1;
    if (sizeof($_SESSION['cart']) != 0) {
        $where_str = "WHERE";
        foreach ($_SESSION['cart'] as $single) {
            if($time != 1) {
                $where_str .= " OR ";
            }
            $where_str.= " productId=" . $single;
            $time++;
        }
        $query.=$where_str;
        $statement = $dbConnection->prepare($query);
        $statement->execute();
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    
?>


<!DOCTYPE html>
<html>
    <head>
        <link href="styles.css" type="text/css" rel="stylesheet" />
        <link rel="shortcut icon" href="/letsGoShopping/includes/tabicon.jpeg">
        <title>Your Shopping Cart</title>
    </head>
    <body>
        <h1 class="heading">Your Shopping Cart</h1>
        <a href="index.php">Return to Otter Express Menu</a>
        <br>
        <table border=1>
        <tr>
        <th> Product Name </th>
        <th> Price </th>
        <th> Calories </th>
        </tr>
            <?php
                $productList = $records;
                $currentTotal = 0;
                if (sizeof($productList) != 0) {
                    foreach($productList as $productItem) {
                        echo "<tr>";
                        echo '<td><a href=index.php?item=' . rawurlencode($productItem['productId']) . '>' . $productItem['productName'] . '</a></td>';
                        echo "<td>" . $productItem['price'] . "</td>";
                        $currentTotal += $productItem['price'];
                        echo "<td>" . $productItem['calories'] . "</td>";
                        echo "</tr>";
                    }
                }
                
                echo "<br><strong>Subtotal</strong>: $" . $currentTotal;
            ?>
    </table>
    <form action='cart_handler.php' method="post">
        <input type="hidden" name="emptyCart" value=1/>
        <input type="submit" name="Empty Cart" value="Empty Cart"/>
    </form>
    </body>
</html>