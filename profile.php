<?php
require('config.php');

if(!empty($_SESSION['CustomerID'])){
    $id_customer=$_SESSION['CustomerID'];
    $result=mysqli_query($conn,"SELECT * FROM customers WHERE CustomerID='$id_customer'");
    $customer_row=mysqli_fetch_assoc($result);

    $query="SELECT * FROM orders 
            INNER JOIN customers 
            ON orders.CustomerID=customers.CustomerID
            INNER JOIN products 
            ON orders.ProductID=products.ProductID
            WHERE customers.CustomerID='$id_customer'";

    $result_orders=mysqli_query($conn,$query);
    
}

if(isset($_POST['delete_order'])){
    $id_order = mysqli_real_escape_string($conn, $_POST['order_id']);
    mysqli_query($conn,"DELETE FROM orders WHERE OrderID='$id_order' AND CustomerID='$id_customer'");
    header("Location: profile.php");
}

if(isset($_POST['update'])){
    $first_name=mysqli_real_escape_string($conn,$_POST["first_name"]);
    $last_name=mysqli_real_escape_string($conn,$_POST["last_name"]);
    $email=mysqli_real_escape_string($conn,$_POST["email"]);
    $password=mysqli_real_escape_string($conn,$_POST["password"]);
    $confirm_password=mysqli_real_escape_string($conn,$_POST["confirm_password"]);
    $phone=mysqli_real_escape_string($conn,$_POST["phone"]);
    $address=mysqli_real_escape_string($conn,$_POST["address"]);

    if($password==$confirm_password){
        mysqli_query($conn,"UPDATE customers 
                        SET FirstName='$first_name',
                            LastName='$last_name',
                            Email='$email',
                            Password='$password',
                            Phone='$phone',
                            Address='$address'
                        WHERE CustomerID='$id_customer'
                        ");
        echo
        "<script> alert('Done');</script>";
    }else{
        echo
        "<script> alert('Password dose not match');</script>";
    }
    
}


if(isset($_POST['delete_account'])){
    $password=mysqli_real_escape_string($conn,$_POST['password']);
    if($password==$customer_row['Password']){
        if(mysqli_query($conn,"DELETE FROM customers WHERE CustomerID='$id_customer'")){
            header("Location: logout.php");
            exit();
        }
    }else{
        echo
        "<script> alert('Password dose not match');</script>";
    }  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
                <h1>Profile <?php
                    if(isset($customer_row['CustomerID'])){
                            echo $customer_row['FirstName'];
                        }
                ?></h1>
                <hr>
                <h3>Orders</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image Product</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row_order=mysqli_fetch_assoc($result_orders)){?>
                        <tr>
                            <td><img src="data:image/jpg;base64,<?php echo base64_encode($row_order['Image']) ?>" style="width: 150px;"></td>
                            <td><?php echo $row_order['ProductName'] ?></td>
                            <td><?php echo $row_order['Quantity'] ?></td>
                            <td><?php echo $row_order['TotalAmount'] ?></td>
                            <td><?php echo date('Y-m-d H:i:s', strtotime($row_order['OrderDate'])); ?></td>
                            <td>
                                <form action="profile.php" method="POST">
                                    <input type="hidden" name="order_id" value="<?php echo $row_order['OrderID']; ?>">
                                    <input type="submit" value="Delete Order" name="delete_order">
                                </form>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <hr>
                <h3>Update Customer Information</h3>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="fn" class="form-label">First Name</label>
                        <input type="text" class="form-control w-50" name="first_name" value="<?php echo $customer_row['FirstName'] ?>" id="fn" require>
                    </div>
                    <div class="mb-3">
                        <label for="ln" class="form-label">Last Name</label>
                        <input type="text" class="form-control w-50" name="last_name" value="<?php echo $customer_row['LastName'] ?>" id="ln" require>
                    </div>
                    <div class="mb-3">
                        <label for="em" class="form-label">Email</label>
                        <input type="email" class="form-control w-50" name="email" value="<?php echo $customer_row['Email'] ?>" id="em" require>
                    </div>
                    <div class="mb-3">
                        <label for="ps" class="form-label">Password</label>
                        <input type="password" class="form-control w-50" name="password"  id="ps" require>
                    </div>
                    <div class="mb-3">
                        <label for="cps" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control w-50" name="confirm_password" id="cps" require>
                    </div>
                    <div class="mb-3">
                        <label for="ph" class="form-label">Phone</label>
                        <input type="number" class="form-control w-50" name="phone" value="<?php echo $customer_row['Phone'] ?>" id="ph" require>
                    </div>
                    <div class="mb-3">
                        <label for="ad" class="form-label">Address</label>
                        <select name="address" class="form-select w-50" id="ad" require >
                            <option value="" selected disabled>Select an Address</option>
                            <option value="Nablus">Nablus</option>
                            <option value="Ramallah">Ramallah</option>
                            <option value="Jenin">Jenin</option>
                            <option value="Tulkarm">Tulkarm</option>
                            <option value="Qalqilya">Qalqilya</option>
                            <option value="Tubas">Tubas</option>
                            <option value="Salfit">Salfit</option>
                            <option value="Jerusalem">Jerusalem</option>
                            <option value="Bethlehem">Bethlehem</option>
                            <option value="Hebron">Hebron</option>
                            <option value="Jericho">Jericho</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="update" value="Update" class="btn btn-primary">
                    </div>
                </form>
                <hr>
                <h3>Delete Account</h3>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="ps" class="form-label">Password</label>
                        <input type="password" class="form-control w-50" name="password" id="ps" require>
                    </div>
                    <div class="mb-3">
                        <input type="submit" name="delete_account" value="delete" class="btn btn-primary ">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

