
<?php include('server/get_all_watches.php'); ?>

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
        <div id="search">
            <div class="title_box">
                <p>Filtr produktow</p>
                <hr>
            </div>
            <form action="shop.php" method="POST">
                <div class="row">
                    <div class="col_1">
                        <p>Kategorie:</p>
                        <div class="form_check">
                            <input type="radio" class="form_check_input" name="category" value="men" id="category_one">
                            <label for="flexRadioDefault1" class="form_check_label">Męskie</label>
                        </div>
                        <div class="form_check">
                            <input type="radio" class="form_check_input" name="category" value="women" id="category_two">
                            <label for="flexRadioDefault1" class="form_check_label">Damskie</label>
                        </div>
                        <div class="form_check">
                            <input type="radio" class="form_check_input" name="category" value="kids" id="category_thre">
                            <label for="flexRadioDefault1" class="form_check_label">Dziecięce</label>
                        </div>
                        <div class="form_check">
                            <input type="radio" class="form_check_input" name="category" value="smartwatch" id="category_fourth">
                            <label for="flexRadioDefault1" class="form_check_label">Smartwatche</label>
                        </div>

                    </div>
                </div>

                <div class="row">
                <p>Cena</p>
                    <span id="actual_price">1000</span>
                    <input type="range" class="form_range" name="price" value="1000" min="1" max="50000" id="customRange2">
                    <div class="price_range">
                        <span style="text-align: left;">1</span>
                        <span style="text-align: right;">50 000</span>
                    </div>
                </div>
                <div class="form_group">
                    <input type="submit" name="search" value="Szukaj" class="btn">
                </div>
            </form>
        </div>    


        <div id="shop_products">
            <div class="container">
                <h3>Nasze nowości</h3>
                <hr>
                <p>Tutaj możesz sprawdzić nasze nowe produkty</p>
            </div>
            <div class="row">
                <?php while($row = $all_watches->fetch_assoc()){ ?>

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

