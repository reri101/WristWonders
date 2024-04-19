<?php



session_start();

include('connection.php');

if(!isset($_SESSION['user']['logged_in'])){
    echo '<script>alert("Aby złożyć zamówienie najpierw musisz się zalogować");</script>';
    echo '<script>window.location="index.php";</script>';
    exit;
}

if(!empty($_SESSION['cart']) && isset($_POST['place_order_btn'])){

    //1. dodanie podstawowych inf o zamowieniu
    $user_id = $_SESSION['user']['id'];
    $order_cost = $_SESSION['total'];
    $order_status_id = 1;
    $order_date = date('Y-m-d H:i:s');

    $stmt = $connection->prepare("INSERT INTO `order` (client_ID, order_date, final_cost, status_ID) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('isii', $user_id, $order_date, $order_cost, $order_status_id);

    $stmt->execute();

    $order_id = $stmt->insert_id;
    $stmt->close();

    $_SESSION['order']['order_id'] = $order_id;
    $_SESSION['order']['finalize'] = false;

    // 2. zebranie produktow z koszyka i dodanie do order_product
    foreach($_SESSION['cart'] as $key => $value){
        $product = $_SESSION['cart'][$key];
        $product_id = $product['product_id'];
        $product_name = $product['product_name'];
        $product_price = $product['product_price'];
        $product_quantity = $product['product_quantity'];

        $stmt2 = $connection->prepare("INSERT INTO `order_product` (order_ID, product_ID, product_quantity, `name`, price) VALUES (?, ?, ?,?,?);");
        $stmt2->bind_param('iiisi',$order_id,$product_id,$product_quantity,$product_name,$product_price);

        $stmt2->execute();
        $stmt2->close();
    }

}else if(isset($_POST['payment_btn'])){
    $order_id = $_SESSION['order']['order_id'];
    $order_status_id = 2;
    $payment_id = $_POST['payment_method'];
    $shipping_id = $_POST['payment_method'];

    $stmt3 = $connection->prepare("UPDATE `order` SET status_ID = ?,shipping_ID=?, payments_ID =? WHERE order_ID = ? AND status_ID = 1");
    $stmt3->bind_param('iiii', $order_status_id, $shipping_id, $payment_id, $order_id);
    $stmt3->execute();

    $_SESSION['order']['finalize'] = true;
    clearProductArray();
    echo '<script>window.location.href = "../thank_you.php";</script>';
} else{
    echo '<script>alert("Nie masz jeszcze żadnych produktów w koszyku");</script>';
    echo '<script>window.location.href = "index.php";</script>';
}

function clearProductArray(){
    foreach($_SESSION['cart'] as $key => $value){
        unset($_SESSION['cart'][$value['product_id']]);
    }

    $_SESSION['total'] = 0;
}


?>