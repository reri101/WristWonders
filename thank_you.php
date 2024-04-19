<?php

session_start();

include('server/connection.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zamówienie - Podsumowanie</title>
    <link rel="stylesheet" href="assets/css/style.css"/>
    <link rel="stylesheet" href="assets/css/thank_you.css"/>
</head>
<body>
<?php include('layouts/header.php'); ?>


    <!-- tank you section -->
    <section class="t_u_s">
        <div class="t_y_container">
            <div class="icon">
                <i class="fa fa-check"></i>
            </div>
            <div class="title">
                Sukces!!
            </div>
            <div class="description">
                <p>Dziękujemy za dokonanie zakupu w naszym sklepie. 🎉</p> 
                <p>Zamówienie niebawem zostanie wysłane.</p> 
            </div>
            <div class="home_page_btn">
                <a href="index.php" id="go_to_home_btn" class="btn">
                    <button id="go_to_home_btn" class="btn">
                    Wróć do sklepu
                    </button>
                </a>
            </div>
        </div>
        <div class="anim-container">
            <div class="burst">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
        </div>
        
</section>
    


    <?php include('layouts/footer.php'); ?>

    <script defer type="text/javascript">
        function createBurst() {
            let animContainer = document.querySelector('.anim-container');
            let burst = document.querySelector('.burst');

            let containerWidth = animContainer.offsetWidth;
            containerWidth = Math.min(containerWidth, 750);
            let containerHeight = animContainer.offsetHeight;
            containerHeight = Math.min(containerWidth, 550);
                
            
            burst.style.top = Math.random() * containerHeight + 'px';
            burst.style.left = Math.random() * containerWidth + 'px';

            let burstClone = burst.cloneNode(true);
            burst.remove();
            animContainer.appendChild(burstClone);

            setTimeout(() => {
                burstClone.remove();
            }, 9000);
        }
        setInterval(createBurst, 900);
    </script>
    <script src="assets/js/main.js"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
</body>
</html>


