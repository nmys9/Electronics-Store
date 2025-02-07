<?php
require('config.php');

if(!empty($_SESSION['CustomerID'])){
    $id_customer=$_SESSION['CustomerID'];
    $query="SELECT * FROM customers WHERE CustomerID='$id_customer'";
    $result=mysqli_query($conn,$query);
    $customer_row=mysqli_fetch_assoc($result);
}


if(isset($_POST['update_product'])){
    $id_product=mysqli_real_escape_string($conn,$_POST['id_product']);
    $query="SELECT products.*,categories.CategoryName 
            FROM products 
            INNER JOIN categories 
            ON products.CategoryID=categories.CategoryID 
            WHERE products.ProductID='$id_product'
            ";

    $result=mysqli_query($conn,$query);
    $row_product=mysqli_fetch_assoc($result);
}

if(isset($_POST['update'])){
    $id_product=mysqli_real_escape_string($conn,$_POST['id_product']);
    $product_name=mysqli_real_escape_string($conn,$_POST["ProductName"]);
    $price=mysqli_real_escape_string($conn,$_POST["Price"]);
    $stock_quantity=mysqli_real_escape_string($conn,$_POST["StockQuantity"]);
    $category_name=mysqli_real_escape_string($conn,$_POST["CategoryName"]);

    $image_query = "";
    if (!empty($_FILES['Image']['tmp_name'])) {
        $image = mysqli_real_escape_string($conn, file_get_contents($_FILES['Image']['tmp_name']));
        $image_query = "Image = '$image',";
    }

    $category_query=mysqli_query($conn,"SELECT CategoryID FROM categories WHERE CategoryName='$category_name'");
    if($category_query){
        $category_row=mysqli_fetch_assoc($category_query);
        $category_id=$category_row['CategoryID'];

        $query_update="UPDATE products 
                        SET ProductName='$product_name',
                        Price='$price',
                        StockQuantity='$stock_quantity',
                        $image_query
                        CategoryID='$category_id'
                        WHERE ProductID='$id_product'
                        ";
                        
        mysqli_query($conn,$query_update);
        header("Location: index.php");
        
    }
}

if(isset($_POST['delete'])){
    $id_product=mysqli_real_escape_string($conn,$_POST['id_product']);
    $query="DELETE FROM products WHERE ProductID='$id_product'";
    mysqli_query($conn,$query);
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
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
                        <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($row_product['CategoryName']); ?></small></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <h1>Update Products</h1>
                <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                    <div class="mb-3">
                        <label for="pn" class="form-label">Product Name:</label>
                        <input type="text" id="pn" name="ProductName" value="<?php echo $row_product['ProductName'] ?>" class="form-control w-75">
                    </div>
                    <div class="mb-3">
                        <label for="p" class="form-label">Price:</label>
                        <input type="text" id="p" name="Price" value="<?php echo $row_product['Price'] ?>" class="form-control w-75">
                    </div>
                    <div class="mb-3">
                        <label for="sq" class="form-label">Stock Quantity:</label>
                        <input type="text" id="sq" name="StockQuantity" value="<?php echo $row_product['StockQuantity'] ?>" class="form-control w-75">
                    </div>
                    <div class="mb-3">
                        <label for="img" class="form-label">Image:</label>
                        <input type="file" id="img" name="Image" class="form-control w-75">
                    </div>
                    <div class="mb-3">
                        <label for="ca" class="form-label">Categories:</label>
                        <select name="CategoryName" class="form-select w-75" id="ca">
                            <option value="" selected disabled>Select a Category</option>
                            <option value="Mobile Phones">Mobile Phones</option>
                            <option value="Personal Electronics">Personal Electronics</option>
                            <option value="Computers">Computers</option>
                            <option value="Computer Accessories">Computer Accessories</option>
                            <option value="Smart Home Devices">Smart Home Devices</option>
                            <option value="Chargers & Batteries">Chargers & Batteries</option>
                        </select>
                    </div>
                    <input type="hidden" name="id_product" value="<?php echo $row_product['ProductID'] ?>">
                    <input type="submit" value="Update Product" name="update" class="btn btn-primary w-25">
                    <input type="submit" value="Delete Product" name="delete" class="btn btn-primary w-25">
                </form>
            </div>
        </div>
    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>