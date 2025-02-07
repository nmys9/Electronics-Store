<?php
require('config.php');

if(!empty($_SESSION['CustomerID'])){
    $id_customer=$_SESSION['CustomerID'];
    $query="SELECT * FROM customers WHERE CustomerID='$id_customer'";
    $result=mysqli_query($conn,$query);
    $customer_row=mysqli_fetch_assoc($result);

    $query="SELECT * FROM orders 
            INNER JOIN customers 
            ON orders.CustomerID=customers.CustomerID
            INNER JOIN products 
            ON orders.ProductID=products.ProductID
            ";

    $result_orders=mysqli_query($conn,$query);
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Electronics Store</a>
            <?php if(!empty($_SESSION['CustomerID']) and $customer_row['FirstName']=='admin' and $customer_row['Email']=='admin@gmail.com'){ ?>
                <ul class="navbar-nav d-flex flex-row">
                    <li class="nav-item">
                        <a href="products.php" class="nav-link">Add Product</a>
                    </li>
                    <li class="nav-item">
                        <a href="customer_orders.php" class="nav-link">Customer Orders</a>
                    </li>
                </ul>
            <?php } ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (empty($_SESSION['CustomerID'])) { ?>
                        <li class="nav-item">
                            <a href="signup.php" class="nav-link">Sign Up</a>
                        </li>
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Log In</a>
                        </li>
                    <?php } else { ?>
                        <li class="nav-item">
                            <a href="profile.php" class="nav-link">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a href="logout.php" class="nav-link">Log Out</a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container">
        <div class="row">
            <div class="col m-3">
                <h1>Customer Orders</h1>
                <hr>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Customer Phone</th>
                            <th>Product Name</th>
                            <th>Image Product</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row_order=mysqli_fetch_assoc($result_orders)){?>
                        <tr>
                            <td><?php echo $row_order['FirstName'] ?></td>
                            <td><?php echo $row_order['Email'] ?></td>
                            <td><?php echo $row_order['Phone'] ?></td>
                            <td><?php echo $row_order['ProductName'] ?></td>
                            <td><img src="data:image/jpg;base64,<?php echo base64_encode($row_order['Image']) ?>" style="width: 150px;"></td>
                            <td><?php echo $row_order['Quantity'] ?></td>
                            <td><?php echo $row_order['TotalAmount'] ?></td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($row_order['OrderDate'])); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <hr>
            </div>
        </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php


