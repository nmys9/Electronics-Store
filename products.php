<?php
require('config.php');

if(!empty($_SESSION['CustomerID'])){
    $id_customer=$_SESSION['CustomerID'];
    $query="SELECT * FROM customers WHERE CustomerID='$id_customer'";
    $result=mysqli_query($conn,$query);
    $customer_row=mysqli_fetch_assoc($result);
    if(!($customer_row['FirstName']=='admin' and $customer_row['Email']=='admin@gmail.com')){
        header("Location: index.php");
    }
}

if(isset($_POST["submit"])){
    $product_name=mysqli_real_escape_string($conn,$_POST["ProductName"]);
    $price=mysqli_real_escape_string($conn,$_POST["Price"]);
    $stock_quantity=mysqli_real_escape_string($conn,$_POST["StockQuantity"]);
    $image=mysqli_real_escape_string($conn,file_get_contents($_FILES['Image']['tmp_name']));
    $category_name=mysqli_real_escape_string($conn,$_POST["CategoryName"]);

    $category_query=mysqli_query($conn,"SELECT CategoryID FROM categories WHERE CategoryName='$category_name'");
    if($category_query){
        $category_row=mysqli_fetch_assoc($category_query);
        $category_id=$category_row['CategoryID'];

        $query="INSERT INTO products(ProductID,ProductName,Price,StockQuantity,Image,CategoryID) 
                VALUES(NULL,'$product_name','$price','$stock_quantity','$image','$category_id')";

        if(mysqli_query($conn,$query)){
            header("Location: index.php");
        }else{
            echo 'error';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Products</title>
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
                <h1>Add Products</h1>
                <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <div class="mb-3">
                        <label for="pn" class="form-label">Product Name:</label>
                        <input type="text" id="pn" name="ProductName" class="form-control w-50">
                    </div>
                    <div class="mb-3">
                        <label for="p" class="form-label">Price:</label>
                        <input type="text" id="p" name="Price" class="form-control w-50">
                    </div>
                    <div class="mb-3">
                        <label for="sq" class="form-label">Stock Quantity:</label>
                        <input type="text" id="sq" name="StockQuantity" class="form-control w-50">
                    </div>
                    <div class="mb-3">
                        <label for="img" class="form-label">Image:</label>
                        <input type="file" id="img" name="Image" class="form-control w-50">
                    </div>
                    <div class="mb-3">
                        <label for="ca" class="form-label">Categories:</label>
                        <select name="CategoryName" class="form-select w-50" id="ca">
                            <option value="" selected disabled>Select a Category</option>
                            <option value="Mobile Phones">Mobile Phones</option>
                            <option value="Personal Electronics">Personal Electronics</option>
                            <option value="Computers">Computers</option>
                            <option value="Computer Accessories">Computer Accessories</option>
                            <option value="Smart Home Devices">Smart Home Devices</option>
                            <option value="Chargers & Batteries">Chargers & Batteries</option>
                        </select>
                    </div>

                    <input type="submit" value="Add Product" name="submit" class="btn btn-primary w-50">
                </form>
            </div>
        </div>
    </div>
    
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
