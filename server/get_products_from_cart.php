<?php

include('connection.php');

$clientID = $_GET['client_ID'];

$stmt_user_products = $connection->prepare("SELECT product.*, watch.name as watch_name, category.category_name
                                           FROM product 
                                           INNER JOIN watch ON product.watch_ID = watch.watch_ID
                                           INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                                           INNER JOIN category ON watch_category.category_ID = category.category_ID 
                                           INNER JOIN user ON product.user_ID = user.client_ID
                                           WHERE user.client_ID = ?");

$stmt_user_products->bind_param("i", $clientID);
$stmt_user_products->execute();

$user_products = $stmt_user_products->get_result();


?>