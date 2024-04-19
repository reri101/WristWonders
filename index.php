<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>
<?php include('layouts/header.php'); ?>

    <!-- home -->
    <section id="home">
        <div class="container">
            <video autoplay muted loop id="boxVideo">
                <source src="assets/imgs/watch_vid.mp4" type="video/mp4">
            </video>
            <h1><span>Najlepsze</span> z dostępnych</h1>
            <p>Znajdź zegarek stworzony dla ciebie</p>
            <a href="shop.php">
                <button>SPRAWDŹ</button>
            </a>
            
        </div>
    </section>

    <!-- brand -->
    <section id="brands" class="container">
        <div class="row">
            <img src="assets/imgs/brand1.png" alt="Image 1"/>
            <img src="assets/imgs/brand2.png" alt="Image 2"/>
            <img src="assets/imgs/brand3.png" alt="Image 3"/>
            <img src="assets/imgs/brand4.png" alt="Image 4"/>
            <img src="assets/imgs/brand5.png" alt="Image 5"/>
        </div>
    </section>

    <!-- new watches -->
    <section id="new" class="w-100">
        <div class="new_watch_one">
            <img src="assets/imgs/new5.jpg">
            <div class="details">
                <h2>Kolekcja moro</h2>
                <h4>NOWOŚĆ</h4>
                <a href="/SklepZZegarkami/shop.php">
                    <button>KUP TERAZ</button>
                </a>
            </div>
        </div>

        <div class="new_watch_one">
            <img src="assets/imgs/new4.png">
            <div class="details">
                <h2>Ekstremalnie wytrzymałe</h2>
                <H4>NOWOŚĆ</H4>
                <a href="/SklepZZegarkami/mens_watches.php">
                    <button>KUP TERAZ</button>
                </a>
            </div>
        </div>

        <div class="new_watch_one">
            <img src="assets/imgs/new3.jpeg">
            <div class="details">
                <h2>Jesienna wyprzedaż damska</h2>
                <h3>30% OFF</h3>
                <a href="/SklepZZegarkami/womens_watches.php">
                    <button>KUP TERAZ</button>
                </a>
            </div>
        </div>
    </section>

    <!-- featured -->
    <section id="newOnes" class="featured">
        <div class="container">
            <h3>Nasze nowości</h3>
            <hr>
            <p>Tutaj możesz sprawdzić nasze nowe sztuki</p>
        </div>
        <div class="row">
            <?php include('server/get_featured_products.php'); ?>
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

    <!-- banner -->
    <section id="banner" class="featured">
        <div class="container">
            <h4>WIELKA WYPRZEDAŻ</h4>
            <h1>Jesienna wyprzedaż <br> NAWET do 30%</h1>
            <button>KUP TERAZ</button>
        </div>
    </section>

    <!-- bands -->
    <section id="bands" class="featured">
        <div class="container">
            <h3>Elegancja i styl</h3>
            <hr>
            <p>Tutaj możesz sprawdzić nasze stylowe zegarki</p>
        </div>
        <div class="row">
            <?php include('server/get_womens_watches.php'); ?>
            <?php while($row = $smartwatches->fetch_assoc()){ ?>

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
    </section>

    <!-- smartwatches -->
    <section id="smartwatches" class="featured">
        <div class="container">
            <h3>Najnowsze smartwatche</h3>
            <hr>
            <p>Sprawdź inteligentne zegarki</p>
        </div>
        <div class="row">
            <?php include('server/get_smartwatches.php'); ?>
            <?php while($row = $smartwatches->fetch_assoc()){ ?>

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
    </section>


    
    <?php include('layouts/cookie_container.php'); ?>
    <?php include('layouts/footer.php'); ?>
    
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
