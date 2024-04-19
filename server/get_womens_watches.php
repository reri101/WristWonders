<?php

include('connection.php');

$stmt_smartwatches = $connection->prepare("SELECT product.*, watch.name as watch_name
                                          FROM product 
                                          INNER JOIN watch ON product.watch_ID = watch.watch_ID
                                          INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                                          INNER JOIN category ON watch_category.category_ID = category.category_ID 
                                          WHERE category.category_name = 'women' 
                                          LIMIT 4");

$stmt_smartwatches->execute();

$smartwatches = $stmt_smartwatches->get_result();
$stmt_smartwatches->close();

?>