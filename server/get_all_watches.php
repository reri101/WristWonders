<?php

include('connection.php');

if(isset($_POST['search'])){
    $category = $_POST['category'];
    $price = $_POST['price'];

    $stmt_all_watches = $connection->prepare("SELECT product.*, watch.name as watch_name, category.category_name as category_name
                                            FROM product 
                                            INNER JOIN watch ON product.watch_ID = watch.watch_ID
                                            INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                                            INNER JOIN category ON watch_category.category_ID = category.category_ID 
                                            WHERE category_name = ? and price <= ?");
    $stmt_all_watches->bind_param("si",$category, $price);
    $stmt_all_watches->execute();

    $all_watches = $stmt_all_watches->get_result();
    $stmt_all_watches->close();
}else{
    $stmt_all_watches = $connection->prepare("SELECT product.*, watch.name as watch_name
                                            FROM product 
                                            INNER JOIN watch ON product.watch_ID = watch.watch_ID
                                            INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                                            INNER JOIN category ON watch_category.category_ID = category.category_ID ");

    $stmt_all_watches->execute();

    $all_watches = $stmt_all_watches->get_result();
    $stmt_all_watches->close();
}



?>