<?php


$stmt_popular_category = $connection->prepare("SELECT c.category_name AS 'Category', SUM(op.product_quantity) AS 'totalValue'
                                                FROM `order_product` as op
                                                JOIN `product` as p ON op.product_ID = p.product_ID
                                                JOIN `watch_category` as wc ON p.watch_ID = wc.watch_ID
                                                JOIN `category` as c ON wc.category_ID = c.category_ID
                                                GROUP BY c.category_name
                                                ORDER BY 'TotalQuantitySold' DESC
                                                LIMIT 1");

$stmt_popular_category->execute();

$the_most_popular = $stmt_popular_category->get_result();
$stmt_popular_category->close();

$t_m_popular_cat = $the_most_popular->fetch_assoc()

?>