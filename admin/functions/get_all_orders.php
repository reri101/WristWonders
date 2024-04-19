<?php


$stmt_all_orders = $connection->prepare("SELECT `order`.*, user.first_name, user.last_name, `status`.status_type
                                       FROM `order`
                                       LEFT JOIN `user` ON user.client_ID = `order`.client_ID
                                       INNER JOIN `status` ON `status`.status_ID = `order`.status_ID
                                       GROUP BY `order`.order_ID
                                       ORDER BY `order`.order_ID DESC
                                       LIMIT 20");

$stmt_all_orders->execute();

$all_orders = $stmt_all_orders->get_result();
$stmt_all_orders->close();

?>