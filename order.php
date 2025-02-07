<?php

require 'config.php';

if (!isset($_SESSION['CustomerID'])) {
    header("Location: login.php");
    exit();
}else{
    $id_customer=$_SESSION['CustomerID'];
    $query="SELECT * FROM customers WHERE CustomerID='$id_customer'";
    $result=mysqli_query($conn,$query);
    $customer_row=mysqli_fetch_assoc($result);
}

if(isset($_POST['submit'])){
    $id_product=mysqli_real_escape_string($conn,$_POST['id_product']);
    $quantity=mysqli_real_escape_string($conn,$_POST['quantity']);
    $query="SELECT * FROM products WHERE ProductID='$id_product' ";

    $result=mysqli_query($conn,$query);
    $row_product=mysqli_fetch_assoc($result);
}

if(isset($_POST['buy'])){
    $id_customer=mysqli_real_escape_string($conn, $_POST['id_customer']);
    $id_product=mysqli_real_escape_string($conn,$_POST['id_product']);
    $quantity=mysqli_real_escape_string($conn,$_POST['quantity']);
    $total_amount=mysqli_real_escape_string($conn,$_POST['total_amount']);
    
    $query="INSERT INTO orders(CustomerID,ProductID,Quantity,TotalAmount)
            VALUES('$id_customer','$id_product','$quantity','$total_amount')";
    mysqli_query($conn,$query);
    
    header("Location: profile.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy now!</title>
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
    <div class="container my-5">
        <div class="row">
            <div class="col">
                <div class="card shadow-sm" style="width: 24rem;">
                    <img src="data:image/jpg;base64,<?php echo base64_encode($row_product['Image']) ?>" 
                        class="card-img-top" 
                        style="object-fit: contain; height: 200px; width: 100%;">
                    <div class="card-body text-center">
                        <h4 class="card-title"><?php echo htmlspecialchars($row_product['ProductName']); ?></h4>
                        <p class="card-text">Price: $<?php echo htmlspecialchars($row_product['Price']); ?></p>
                        <p class="card-text">Stock: <?php echo htmlspecialchars($row_product['StockQuantity']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <input type="hidden" name="id_customer" value="<?php echo $id_customer ?>" >
                    <input type="hidden" name="id_product" value="<?php echo htmlspecialchars($row_product['ProductID']); ?>">
                    <div class="mb-3">
                        <label for="q" class="form-label">Quantity</label>    
                        <input type="number" id="q" name="quantity" class="form-control w-50" value="<?php echo $quantity ?>" readonly >
                    </div>
                    <div class="mb-3">
                        <label for="ta" class="form-label">Total Amount</label>
                        <input type="text" id="ta" name="total_amount" class="form-control w-50" value="<?php echo ($quantity*$row_product['Price']); ?>" readonly>
                    </div>

                    <input type="submit" value="Buy Now!" name="buy" class="btn btn-primary w-50">
                </form>
                
            </div>
        </div>

    </div>
    
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>