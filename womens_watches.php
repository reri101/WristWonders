
<?php

include('server/connection.php');

$tmp['category_name'] = "women";

$stmt2 = $connection->prepare("SELECT product.*, watch.name as watch_name
                            FROM product 
                            INNER JOIN watch ON product.watch_ID = watch.watch_ID
                            INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                            INNER JOIN category ON watch_category.category_ID = category.category_ID 
                            WHERE category.category_name = ? ");
                            
$stmt2->bind_param('s',$tmp['category_name']);
$stmt2->execute();

$womens_watches = $stmt2->get_result();
$stmt2->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/shop.css"/>
</head>
<body>
<?php include('layouts/header.php'); ?>


    <!-- search and products-->
    <section id="newOnes" class="featured">
         <div id="shop_products new_items_container">
            <div class="container">
                <h3>Specjalnie dla kobiet</h3>
                <hr>
                <p>Odkryj Wyjątkowe Zegarki Damskie, które Podkreślą Twój Styl i Urok</p>
            </div>
            <div class="row">
                <?php while($row = $womens_watches->fetch_assoc()){ ?>

                <div class="product">
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
        </div>
    </section>

    <?php include('layouts/cookie_container.php'); ?>
    <?php include('layouts/footer.php'); ?>
    
    <script>
        var elem = document.querySelector('#customRange2');

		var rangeValue = function(){
		  var newValue = elem.value;
		  var target = document.querySelector('#actual_price');
		  target.innerHTML = newValue;
		}

		elem.addEventListener("input", rangeValue);
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>

