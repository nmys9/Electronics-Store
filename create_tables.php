<?php

require('config.php');

$table_customers="CREATE TABLE IF NOT EXISTS customers(
                    CustomerID INT AUTO_INCREMENT PRIMARY KEY,
                    FirstName VARCHAR(20) NOT NULL,
                    LastName VARCHAR(20) NOT NULL,
                    Email VARCHAR(50) NOT NULL UNIQUE,
                    Password VARCHAR(50) NOT NULL,
                    Phone INT(20) NOT NULL,
                    Address VARCHAR(50) NOT NULL
                    );";

$table_categories="CREATE TABLE IF NOT EXISTS categories(
                    CategoryID INT AUTO_INCREMENT PRIMARY KEY,
                    CategoryName VARCHAR(50) NOT NULL UNIQUE
                    );";

$table_products="CREATE TABLE IF NOT EXISTS products(
                    ProductID INT AUTO_INCREMENT PRIMARY KEY,
                    ProductName VARCHAR(200) NOT NULL,
                    Price DECIMAL(10,2) NOT NULL,
                    StockQuantity INT NOT NULL DEFAULT 0,
                    Image BLOB NOT NULL,
                    CategoryID INT NOT NULL,
                    FOREIGN KEY(CategoryID) REFERENCES categories(CategoryID) 
                        ON DELETE CASCADE 
                        ON UPDATE CASCADE
                    );";

$table_orders="CREATE TABLE IF NOT EXISTS orders(
                OrderID INT AUTO_INCREMENT PRIMARY KEY,
                CustomerID INT NOT NULL,
                ProductID INT NOT NULL,
                Quantity INT NOT NULL DEFAULT 1,
                TotalAmount DECIMAL(10,2) NOT NULL,
                OrderDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY(CustomerID) REFERENCES customers(CustomerID) 
                    ON DELETE CASCADE 
                    ON UPDATE CASCADE,
                FOREIGN KEY(ProductID) REFERENCES products(ProductID)
                    ON DELETE CASCADE
                    ON UPDATE CASCADE
                );";


if(mysqli_query($conn,$table_customers) 
    && mysqli_query($conn,$table_categories) 
    && mysqli_query($conn,$table_products) 
    && mysqli_query($conn,$table_orders)){
    echo 'done';
}else{
    echo 'error';
}

//insert data for categories table
$categories=array(
    'Mobile Phones',
    'Personal Electronics',
    'Computers',
    'Computer Accessories',
    'Smart Home Devices',
    'Chargers & Batteries'
);
// foreach ($categories as $key=>$categorie) {
//     $query="INSERT INTO `categories`(CategoryID,CategoryName) VALUES(NULL,'$categorie')";  
//     if(mysqli_query($conn,$query)){
//         echo 'done';
//     }else{
//         echo 'error';
//     }
// }




?>
