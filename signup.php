<?php
require "config.php";

if(!empty($_SESSION['CustomerID'])){
    header("Loccation: index.php");
}

if(isset($_POST["submit"])){
    $first_name=mysqli_real_escape_string($conn,$_POST["first_name"]);
    $last_name=mysqli_real_escape_string($conn,$_POST["last_name"]);
    $email=mysqli_real_escape_string($conn,$_POST["email"]);
    $password=mysqli_real_escape_string($conn,$_POST["password"]);
    $confirm_password=mysqli_real_escape_string($conn,$_POST["confirm_password"]);
    $phone=mysqli_real_escape_string($conn,$_POST["phone"]);
    $address=mysqli_real_escape_string($conn,$_POST["address"]);
    $duplicate=mysqli_query($conn,"SELECT * FROM customers WHERE Email='$email'");
    if(mysqli_num_rows($duplicate)>0){
        echo 
            "<script> alert('this customers has already taken');</script>";
    }else{
        if($password==$confirm_password){
            $query="INSERT INTO customers VALUES('','$first_name','$last_name','$email','$password','$phone','$address')";
            mysqli_query($conn,$query);
            header("Location: login.php");
        }else{
            echo
            "<script> alert('Password dose not match');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Electronics Store</a>
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
        <div class="row ">
            <div class="col m-3">
                <h1>Create Account</h1>
                <form action="" method="post">
                    <div class="mb-3">
                        <label for="fn" class="form-label">First Name</label>
                        <input type="text" class="form-control w-50" name="first_name" id="fn" require>
                    </div>
                    <div class="mb-3">
                        <label for="ln" class="form-label">Last Name</label>
                        <input type="text" class="form-control w-50" name="last_name" id="ln" require>
                    </div>
                    <div class="mb-3">
                        <label for="em" class="form-label">Email</label>
                        <input type="email" class="form-control w-50" name="email" id="em" require>
                    </div>
                    <div class="mb-3">
                        <label for="ps" class="form-label">Password</label>
                        <input type="password" class="form-control w-50" name="password" id="ps" require>
                    </div>
                    <div class="mb-3">
                        <label for="cps" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control w-50" name="confirm_password" id="cps" require>
                    </div>
                    <div class="mb-3">
                        <label for="ph" class="form-label">Phone</label>
                        <input type="number" class="form-control w-50" name="phone" id="ph" require>
                    </div>
                    <div class="mb-3">
                        <label for="ad" class="form-label">Address</label>
                        <select name="address" class="form-select w-50" id="ad" require>
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
                        <input type="submit" name="submit" value="sign Up" class="btn btn-primary">
                    </div>
                </form>
                <p>Already have  an Account <a href="login.php" class="btn btn-primary">Log In</a></p>
                <a href="index.php" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>