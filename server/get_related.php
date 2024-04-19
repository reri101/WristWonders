<?php

include('connection.php');

$stmt = $connection->prepare("SELECT product.*, watch.name as watch_name
                            FROM product 
                            INNER JOIN watch ON product.watch_ID = watch.watch_ID
                            INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                            INNER JOIN category ON watch_category.category_ID = category.category_ID 
                            WHERE category.category_name = ? 
                            LIMIT 4");

$stmt->bind_param('s',$row['category_name']);

$stmt->execute();

$featured_products = $stmt->get_result();
$stmt->close();



?>