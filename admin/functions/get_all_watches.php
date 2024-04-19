<?php


$stmt_all_watches = $connection->prepare("SELECT product.*, watch.name as watch_name,watch.watch_ID as watch_id, category.category_name as category_name
                                        FROM product 
                                        INNER JOIN watch ON product.watch_ID = watch.watch_ID
                                        INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                                        INNER JOIN category ON watch_category.category_ID = category.category_ID 
                                        ORDER BY product.product_ID ASC");

$stmt_all_watches->execute();

$all_watches = $stmt_all_watches->get_result();
$stmt_all_watches->close();

?>