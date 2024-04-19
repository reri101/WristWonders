<!-- footer -->
<footer>
        <div class="footer-info row container">
            <div class="footer-one">
                <div class="brand"><a href="/index.html"><span>W</span>rist<span>W</span>onders</a></div>
                <p>Dostarczamy najlepsze zegarki stworzone dla ciebie</p>
            </div>
            <div class="footer-one">
                <h5>Wyróżnione</h5>
                <ul class="wyroznione_list">
                    <li><a href="/SklepZZegarkami/shop.php">SKLEP</a></li>
                    <li><a href="/SklepZZegarkami/new_watches.php">NOWOŚCI</a></li>
                    <li><a href="/SklepZZegarkami/mens_watches.php">MĘŻCZYŹNI</a></li>
                    <li><a href="/SklepZZegarkami/womens_watches.php">KOBIETY</a></li>
                </ul>
            </div>

            <div class="footer-one">
                <h5>Kontakt</h5>
                <div>
                    <h6>ADRES</h6>
                    <p>ul. Kazimeirza Maurycego 21, Malbork</p>
                </div>
                <div>
                    <h6>TELEFON</h6>
                    <p>423 123 123</p>
                </div>
                <div>
                    <h6>EMAIL</h6>
                    <p>kontakt@writstwonders.com</p>
                </div>
            </div>
            <div class="footer-one">
                <h5>Nasze zdjęcia</h5>
                <div class="items-img">
                    <?php include('server/get_ig_products.php'); ?>
                    <?php while($row = $ig_products->fetch_assoc()){ ?>

                    <img src=".<?php echo $row['image_third']; ?>" alt="">

                    <?php }?>
                </div>
            </div>
        </div>

        <div class="copyright">
            <div class="row container">
                <div class="img-col">
                    <img src="assets/imgs/blik.png" alt="">
                    <img src="assets/imgs/przelewy.png" alt="">
                    <img src="assets/imgs/inpost.png" alt="">
                </div>
                <div class="rights-col">
                    <p>WristWonders @ 2023 Wszelkie prawa zastrzeżone</p>
                </div>
                <div class="media-col">
                    <a href="https://www.facebook.com"><i class='fab fa-facebook-f'></i></a>
                    <a href="https://www.instagram.com"><i class='fab fa-instagram'></i></i></a>
                    <a href="https://twitter.com"><i class='fab fa-twitter'></i></a>
                </div>
            </div>            
        </div>
    </footer>