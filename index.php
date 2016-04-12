<!--DONEZO     Database has at least 3 tables with 40 records (10 points)-->
<!--DONEZO     Users can filter data using at least three fields (15 points)-->
<!--DONEZO     Users can sort results (asc,desc) using at least one field (10 points)-->
<!--DONEZO     Users can click on an item to get further info (10 points)-->
<!--DONEZO     Users can add items to shopping cart using a Session (10 points)-->
<!--DONEZO     Users can see the content of the shopping cart (10 points)-->
<!--DONEZO     The web pages have a nice and consistent look and feel (10 points)-->
<!--           The team used Github for collaboration (10 points)-->
<!--DONEZO     The team used Trello or a similar tool for project management (10 points)-->
<!--           In a Word document include User Story, Database schema, and mock up (5 points)-->

<?php
    session_start();
    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
    }
    include('includes/database.php');
    
    $dbConnection = getDatabaseConnection();
    
    function getProductList() {
        global $dbConnection;
    
        $sql = "SELECT productName, price, calories, productTypeId, productId FROM Product WHERE 1";
    
        if (isset($_GET['searchForm'])) { //checks whether the search form was submitted
    
            $namedParameters = array();

            if (!empty($_GET['productType'])) {
                $sql .= " AND productTypeId=" . $_GET['productType'] . " "; //Using Named Parameters to prevent SQL Injection
            }

            if (!empty($_GET['maxPrice'])) {
                $sql .= " AND price <= :maxPrice ";
                $namedParameters[":maxPrice"] =  $_GET['maxPrice'];
            }

            if (isset($_GET['healthyChoice'])) {
                $sql .= " AND healthyChoice=1";
            }

            if (isset($_GET['orderBy'])) {
                $sql .= " ORDER BY " . $_GET['orderBy'];
            }
            
            if (isset($_GET['ascdesc'])) {
                $sql .= " " . $_GET['ascdesc'];
            }
        }

        else {
            $sql .= " ORDER BY Price ASC";
        }

        $statement = $dbConnection->prepare($sql);
        $statement->execute($namedParameters);
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }
?>

<!DOCTYPE HTML>
<html>
<head>
    <link href="styles.css" type="text/css" rel="stylesheet" />
    <link rel="shortcut icon" href="/letsGoShopping/includes/tabicon.jpeg">
    <title>Let's go shopping!</title>

</head>
<body>

    <h1 class="heading">Otter Express Menu</h1>
    <form class="itemList" method="post" action="cart_handler.php">

    <input class="sub" type="submit" value="Add Selections to Cart" name="addForm">
    <br>
    <a href="shoppingCart.php">View Cart
    <?php
        echo" (";
        $cartSize = sizeof($_SESSION['cart']);
        echo $cartSize . ")";
    ?></a>
    <br>
    
    <table border=1>
        <tr>
        <th> Product Name </th>
        <th> Price </th>
        <th> Calories </th>
        <th>Select Item</th>
        <th> Product Name </th>
        <th> Price </th>
        <th> Calories </th>
        <th>Select Item</th>
        </tr>
            <?php
                $itemCount = 1;
                $productList = getProductList();
                foreach($productList as $productItem) {
                    if($itemCount % 2 == 1) {
                        echo "<tr>";
                    }
                    echo '<td><a href=index.php?item=' . rawurlencode($productItem['productId']) . '>' . $productItem['productName'] . '</a></td>';
                    echo "<td>" . $productItem['price'] . "</td>";
                    echo "<td>" . $productItem['calories'] . "</td>";
                    echo "<td>" . '<input type="checkbox" name="itemToCart[]" value="'. $productItem['productId'] . '">';
                    if($itemCount %2 == 0) {
                        echo "</tr>";
                    }
                    $itemCount++;
                }
            ?>
    </table>
    </form>
    <form>
        Product Type: 
        <select name="productType">
            <option value=""> All </option>
            <option value="1"> Veggies </option>
            <option value="2"> Dairy </option>
            <option value="3"> Protein </option>
            <option value="4"> Grains </option>
            <option value="5"> Fruit </option>
        </select>
        Max. Price:
        <input type="text" name="maxPrice" size=5>
        <input type="checkbox" name="healthyChoice"> Healthy Choice
        <br>
        Order results by: 
        <input type="radio" name="orderBy" value="productName"> Product Name /
        <input type="radio" name="orderBy" value="price" checked> Price <br />
        Ordered in:
        <input type="radio" name="ascdesc" value="DESC"> Descending /
        <input type="radio" name="ascdesc" value="ASC" checked> Ascending <br />
        <input class="sub" type="submit" value="Search Products" name="searchForm">
    </form>
    
    <!--display description-->
    <div class="itemDescription">
        <?php
            if (isset($_GET['item'])) {
                $item_select = "SELECT * FROM Product WHERE productId LIKE '" . $_GET['item'] . "'";
                $itemLookup = $dbConnection->prepare($item_select);
                $itemLookup->execute();
                
                while($row = $itemLookup->fetch()) {
                    echo "<strong>" . $row['productName'] . "<strong><br>";
                    echo "<p>" . $row['productDesc'] . "</p>";
                }
            }
        ?>
    </div>
</body>
</html>