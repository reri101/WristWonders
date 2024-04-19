<?php

session_start();

if(isset($_POST['add_to_cart'])){
    if(isset($_SESSION['cart'])){
        $products_array_ids = array_column($_SESSION['cart'],'product_id');
        if(!in_array($_POST['product_id'], $products_array_ids)){
            $product_array = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'product_quantity' => $_POST['product_quantity'],
                'product_image' => $_POST['product_image']

            );

            $product_id =  $_POST['product_id'];
            $_SESSION['cart'][$product_id] = $product_array;
            calculateTotal();
        }else{
            echo '<script>alert("Zegarek został już dodany do koszyka");</script>';
            //echo '<script>window.location="index.php";</script>';
        }
    }else{
        $product_id =  $_POST['product_id'];
        $product_name =  $_POST['product_name'];
        $product_price =  $_POST['product_price'];
        $product_quantity =  $_POST['product_quantity'];
        $product_image = $_POST['product_image'];

        $product_array = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_quantity' => $product_quantity,
            'product_image' => $product_image
        );

        $_SESSION['cart'][$product_id] = $product_array;
        calculateTotal();
    }

}elseif(isset($_POST['remove_product'])){
    $product_id =  $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    calculateTotal();

}elseif(isset($_POST['edit_quantity'])){
    $product_id =  $_POST['product_id'];
    $product_quantity =  $_POST['product_quantity'];
    $product_array = $_SESSION['cart'][$product_id];
    if($product_quantity<1){
        unset($_SESSION['cart'][$product_id]);
    }
    else{
        $product_array['product_quantity'] = $product_quantity;
        $_SESSION['cart'][$product_id] = $product_array;
    }
    
    calculateTotal();
}else{

}


function calculateTotal(){
    $total = 0;

    foreach($_SESSION['cart'] as $key => $value){
        $product = $_SESSION['cart'][$key];
        $price = $product['product_price'];
        $quantity = $product['product_quantity'];

        $total += $price * $quantity;
    }
    $_SESSION['total'] = $total;
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/cart5.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include('layouts/header.php'); ?>

    <!-- cart -->
    <section class="your-cart container">
        <div class="title-container">
            <h2>Twój koszyk</h2>
            <hr>
        </div>
        <table class="tabela">
            <thead>
                <tr>
                    <th>Produkty</th>
                    <th>Ilość</th>
                    <th>Wartość</th>
                </tr>
            </thead>
            <tbody>
            <p class="empty_cart" style="color: red;"><?php if(empty($_SESSION['cart'])){ echo "Twój koszyk jest pusty";}?></p>
            <?php if(!empty($_SESSION['cart'])) foreach($_SESSION['cart'] as $key => $value){ ?>
            
                <tr>
                    <td>
                            <div class="product-info">
                            <img src=".<?php echo $value['product_image']; ?>" alt="">
                            <div>
                                <p><?php echo $value['product_name']; ?></p>
                                <small><?php echo $value['product_price']; ?><span> zł</span></small>
                                <br>
                                <form method="POST" action="cart.php">
                                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                                    <input type="submit" name="remove_product" class="remove-btn" value="usuń">
                                </form>
                            </div>
                        </div>
                    </td>
                    <td class="edit_quantity_td">
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>">
                            <input type="number" name="product_quantity" class="quantity" value="<?php echo $value['product_quantity']; ?>" min="0">
                            <input type="submit" name="edit_quantity" class="edit-btn" value="edytuj">
                        </form>
                    </td>
                    <td>
                        <span class="product-price"><?php echo $value['product_price'] * $value['product_quantity']; ?></span>
                        <span> zł</span>
                    </td>
                </tr>

            <?php }?>
            </tbody>

        </table>

        <div class="cart-total">
            <table>
                <tr>
                    <td>Suma:</td>
                    <td><?php if(!empty($_SESSION['cart'])) echo $_SESSION['total']; ?> zł</td>
                </tr>
                <tr>
                    <td>Suma z dostawą(10 zł):</td>
                    <td><?php if(!empty($_SESSION['cart'])) echo $_SESSION['total'] + 10; ?> zł</td>
                </tr>
            </table>
        </div>

        <div class="checkout-container">
            <form method="POST" action="checkout.php">
                <input type="submit" name="place_order_btn" class="btn" id="payment-btn" value="Płatność">
            </form>
        </div>

        
    </section>



    <?php include('layouts/cookie_container.php'); ?>
    <?php include('layouts/footer.php'); ?>

    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>