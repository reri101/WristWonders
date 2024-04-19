<?php

include('connection.php');

$tmp['category_name'] = "men";

$stmt2 = $connection->prepare("SELECT product.*, watch.name as watch_name
                            FROM product 
                            INNER JOIN watch ON product.watch_ID = watch.watch_ID
                            INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                            INNER JOIN category ON watch_category.category_ID = category.category_ID 
                            WHERE category.category_name = ? 
                            LIMIT 4");
                            
$stmt2->bind_param('s',$tmp['category_name']);
$stmt2->execute();

$featured_products = $stmt2->get_result();
$stmt2->close();



?>