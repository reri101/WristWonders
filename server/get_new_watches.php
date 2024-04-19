<?php

include('connection.php');


$stmt_new_watches = $connection->prepare("SELECT product.*, watch.name as watch_name
                                        FROM product 
                                        INNER JOIN watch ON product.watch_ID = watch.watch_ID
                                        INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                                        INNER JOIN category ON watch_category.category_ID = category.category_ID 
                                        ORDER BY product.product_ID DESC");

$stmt_new_watches->execute();

$new_watches = $stmt_new_watches->get_result();
$stmt_new_watches->close();



?>