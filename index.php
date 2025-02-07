<?php
require('config.php');

if(!empty($_SESSION['CustomerID'])){
    $id_customer=$_SESSION['CustomerID'];
    $query="SELECT * FROM customers WHERE CustomerID='$id_customer'";
    $result=mysqli_query($conn,$query);
    $customer_row=mysqli_fetch_assoc($result);
}

if(isset($_POST['submit'])){
    $category_name=mysqli_real_escape_string($conn,$_POST['CategoryName']);
    $category_query=mysqli_query($conn,"SELECT CategoryID FROM categories WHERE CategoryName='$category_name'");

    if($category_query){
        $category_row=mysqli_fetch_assoc($category_query);
        $category_id=$category_row['CategoryID'];

    $query="SELECT products.*,categories.CategoryName 
        FROM products 
        INNER JOIN categories 
        ON products.CategoryID=categories.CategoryID 
        WHERE products.CategoryID='$category_id'";
    }
}else{
    $query="SELECT products.*,categories.CategoryName 
        FROM products 
        INNER JOIN categories 
        ON products.CategoryID=categories.CategoryID ";
}

$result=mysqli_query($conn,$query);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Electronics Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Electronics Store</a>
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

    <div class="container my-5">
        <div class="row">
            <div class="col">
                <h1 class="text-center mb-4">Welcome 
                    <?php 
                        if(!empty($_SESSION['CustomerID'])){
                            echo $customer_row['FirstName'];
                        }else{
                            echo 'guest';
                        }
                    ?>
                </h1>
                <?php if(!empty($_SESSION['CustomerID']) and $customer_row['FirstName']=='admin' and $customer_row['Email']=='admin@gmail.com'){ ?>
                <div class="text-center mb-4">
                    <a href="products.php" class="btn btn-primary">Add Product</a>
                </div>
                <?php } ?>
                <div class="text-center mb-4">
                    <form action="" method="POST">
                        <label for="ca" class="form-label">Categories:</label>
                        <select name="CategoryName" class="form-select w-50 mx-auto" id="ca">
                            <option value="" selected disabled>Select a Category</option>
                            <option value="Mobile Phones">Mobile Phones</option>
                            <option value="Personal Electronics">Personal Electronics</option>
                            <option value="Computers">Computers</option>
                            <option value="Computer Accessories">Computer Accessories</option>
                            <option value="Smart Home Devices">Smart Home Devices</option>
                            <option value="Chargers & Batteries">Chargers & Batteries</option>
                        </select>
                        <button type="submit" name="submit" class="btn btn-primary mt-3">View</button>
                    </form>
                </div>
                <div class="d-flex flex-wrap justify-content-center gap-4">
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                    <div class="card shadow-sm" style="width: 24rem;">
                        <img src="data:image/jpg;base64,<?php echo base64_encode($row['Image']) ?>" 
                            class="card-img-top" 
                            style="object-fit: contain; height: 200px; width: 100%;">
                        <div class="card-body text-center">
                            <h4 class="card-title"><?php echo htmlspecialchars($row['ProductName']); ?></h4>
                            <p class="card-text">Price: $<?php echo htmlspecialchars($row['Price']); ?></p>
                            <p class="card-text">Stock: <?php echo htmlspecialchars($row['StockQuantity']); ?></p>
                            <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($row['CategoryName']); ?></small></p>
                        </div>
                        <div class="card-footer">
                            <form action="order.php" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="id_customer" value="<?php echo $id_customer ?>" >
                                <input type="hidden" name="id_product" value="<?php echo htmlspecialchars($row['ProductID']); ?>">
                                <label for="q" class="form-label me-2">Quantity</label>
                                <input type="number" name="quantity" id="q" class="form-control text-center me-2" style="max-width: 150px;" value="1" min="1" max="<?php echo $row['StockQuantity'] ?>">
                                <input type="submit" name="submit" class="btn btn-primary w-50" value="Buy">
                            </form>
                            <?php if(!empty($_SESSION['CustomerID']) and $customer_row['FirstName']=='admin' and $customer_row['Email']=='admin@gmail.com'){ ?>
                                <form action="update_delete_product.php" method="POST" class="d-flex justify-content-center mt-3">
                                    <input type="hidden" name="id_product" value="<?php echo $row['ProductID'] ?>">
                                    <input type="submit" value="Update" name="update_product" class="btn btn-primary w-50 mx-4 ">
                                    
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php


