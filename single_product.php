<?php

include('server/connection.php');

if(isset($_GET['product_ID'])){

    $product_id = $_GET['product_ID'];
    $stmt_watch = $connection->prepare("SELECT product.*, watch.name as watch_name, watch.watch_ID as 'watch_ID', watch.description as 'description', category.category_name as category_name
                                            FROM product 
                                            INNER JOIN watch ON product.watch_ID = watch.watch_ID
                                            INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                                            INNER JOIN category ON watch_category.category_ID = category.category_ID 
                                            WHERE product.product_ID = ?
                                            LIMIT 1");

    $stmt_watch->bind_param("i",$product_id);
    $stmt_watch->execute();

    $watch = $stmt_watch->get_result();
    $stmt_watch->close();

    $tmp = $watch->fetch_assoc();

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

}else{
    header('location: index.php');
}





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/single_product.css"/>
</head>
<body>
<?php include('layouts/header.php'); ?>

    <!-- single product -->
    <section class="single-product">
        <div class="row">
            <?php $row = $tmp; ?>    

            <div class="img-col">
                <img id="main-product-img" src=".<?php echo $row['image_first']; ?>" alt=""/>
                <div class="small-img-group">
                    <div class="small-img-col">
                        <img src=".<?php echo $row['image_first']; ?>" class="small-img" alt=""/>
                    </div>
                    <div class="small-img-col">
                        <img src=".<?php echo $row['image_second']; ?>" class="small-img" alt=""/>
                    </div>
                    <div class="small-img-col">
                        <img src=".<?php echo $row['image_third']; ?>" class="small-img" alt=""/>
                    </div>
                    <?php if($row['image_fourth']!=NULL){?>
                        <div class="small-img-col">
                            <img src=".<?php echo $row['image_fourth']; ?>" class="small-img" alt=""/>
                        </div>
                    <?php }?>
                </div> 
            </div>

            <div class="text-col">
                <h6><?php echo $row['category_name']; ?></h6>
                <h3><?php echo $row['watch_name']; ?></h3>
                <h2><?php echo $row['price']; ?> zł</h2>

                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_ID']; ?>">
                    <input type="hidden" name="product_image" value="<?php echo $row['image_first']; ?>">
                    <input type="hidden" name="product_name" value="<?php echo $row['watch_name']; ?>">
                    <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                    <input type="number" name="product_quantity" value="1" min="0"/>
                    <button class="buy-button" type="submit" name="add_to_cart">Dodaj do koszyka</button>
                </form>

                <h4>Szczegóły</h4>
                <span><?php echo $row['description']; ?></span>
            </div>
        </div>
    </section>


<!-- related produts -->
    <section id="related-products" class="featured">
        <div class="container">
            <h3>Powiązane produkty</h3>
            <hr>
        </div>
        <div class="row">
            <?php while($row = $featured_products->fetch_assoc()){ ?>

            <div class="product" >
                <a class="href_on_img" href="<?php echo "single_product.php?product_ID=" . $row['product_ID']; ?>">
                    <img src=".<?php echo $row['image_first']; ?>" />
                </a>
                <div class="stars">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $row['watch_name']; ?></h5>
                <h4 class="p-price"><?php echo $row['price']; ?> zł</h4>
                <a href="<?php echo "single_product.php?product_ID=". $row['product_ID'];?>"><button class="buy-btn">KUP TERAZ</button></a>
            </div>

            <?php }?>
        </div>
    </section>

    <?php include('layouts/cookie_container.php'); ?>
    <?php include('layouts/footer.php'); ?>

    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>
