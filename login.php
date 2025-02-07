<?php

require('config.php');

if(!empty($_SESSION['CustomerID'])){
    header("Location: index.php");
}

if(isset($_POST["submit"])){
    $email=mysqli_real_escape_string($conn,$_POST["email"]);
    $password=mysqli_real_escape_string($conn,$_POST["password"]);
    $query="SELECT * FROM customers WHERE Email='$email'";
    $result=mysqli_query($conn,$query);
    $row=mysqli_fetch_assoc($result);
    if(mysqli_num_rows($result)>0){
        if($password==$row["Password"]){
            $_SESSION['login']=true;
            $_SESSION['CustomerID']=$row["CustomerID"];
            header("Location: index.php");
        }else{
            echo "<script> alert('Wrong Password');</script>";
        }
    }else{
        echo "<script> alert('Customers not found');</script>";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
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
        <div class="row">
            <div class="col m-3">
                <h1>Log In</h1>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="em" class="form-label">Email</label>
                        <input type="email" name="email" id="em" class="form-control w-50">
                    </div>
                    <div class="mb-3">
                        <label for="pa" class="form-label">Password</label>
                        <input type="password" name="password" id="pa" class="form-control w-50">
                    </div>
                    <input type="submit" value="Log In" class="btn btn-primary" name="submit">
                </form>
                <br>
                <p>If you don't have an Account <a href="signup.php" class="btn btn-primary">Sign Up</a></p>
                <a href="index.php" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    
    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>