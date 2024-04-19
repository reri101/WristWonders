<?php

session_start();
include('../server/connection.php');

if(!isset($_SESSION['admin']['logged_in'])){
    header('location: login.php');
    exit;
}

if(isset($_GET['logout'])){
    if(isset($_SESSION['admin']['logged_in'])){
        unset($_SESSION['admin']);
        header('location: login.php');
        exit;
    }
}

if(isset($_POST['add_product_btn'])){
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $mechanism = $_POST['mechanism'];
    $description = $_POST['description'];
    $waterproof = $_POST['waterproof'].' m';
    $clasp_type = $_POST['clasp_type'];
    $dial_colour = $_POST['dial_colour'];
    $image_1 = $_FILES['image_1']['name'];
    $image_2 = $_FILES['image_2']['name'];
    $image_3 = $_FILES['image_3']['name'];
    $image_4 = $_FILES['image_4']['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $selected_category = $_POST['selected_category'];

    $path="/assets/imgs/zegarki/".$selected_category."/";

    $image_extension1 = pathinfo($image_1, PATHINFO_EXTENSION);
    $filename1 = time() . '_1.' . $image_extension1;
    $image_extension2 = pathinfo($image_2, PATHINFO_EXTENSION);
    $filename2 = time() . '_2.' . $image_extension2;
    $image_extension3 = pathinfo($image_3, PATHINFO_EXTENSION);
    $filename3 = time() . '_3.' . $image_extension3;
    $image_extension4 = pathinfo($image_4, PATHINFO_EXTENSION);
    $filename4 = time() . '_4.' . $image_extension4;

    $file_path1 = $path.$filename1;
    $file_path2 = $path.$filename2;
    $file_path3 = $path.$filename3;
    $file_path4 = $path.$filename4;

    $stmt_watch = $connection->prepare("INSERT INTO watch (`name`, mechanism, waterproof, clasp_type, dial_Colour, `description`, brand) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt_watch->bind_param('sssssss', $name, $mechanism, $waterproof, $clasp_type, $dial_colour, $description, $brand);
    if($stmt_watch->execute()){
        $watch_id = $stmt_watch->insert_id;
        $stmt_watch->close();


        $stmt_product = $connection->prepare("INSERT INTO product (watch_ID, price, `availability`, image_first, image_second, image_third, image_fourth) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt_product->bind_param('iiissss', $watch_id, $price, $quantity, $file_path1, $file_path2, $file_path3, $file_path4);
        if($stmt_product->execute()){
            $stmt_product->close();

            $stmt_category = $connection->prepare("SELECT * FROM category WHERE category_name = ? LIMIT 1");
            $stmt_category->bind_param('s',$selected_category);
            if($stmt_category->execute()){
                $category = $stmt_category->get_result();
                $stmt_category->close();
                $tmp_cat = $category->fetch_assoc();

                $stmt_add_to_cat = $connection->prepare("INSERT INTO watch_category (watch_ID, category_ID) VALUES (?, ?)");
                $stmt_add_to_cat->bind_param('ii', $watch_id, $tmp_cat['category_ID']);
                if($stmt_add_to_cat->execute()){
                    $stmt_add_to_cat->close();

                    $path_v2 = "..".$path;
                    $file_tmp = $_FILES['image_1']['tmp_name'];
                    move_uploaded_file($file_tmp, $path_v2.$filename1);
                    $file_tmp2 = $_FILES['image_2']['tmp_name'];
                    move_uploaded_file($file_tmp2, $path_v2.$filename2);
                    $file_tmp3 = $_FILES['image_3']['tmp_name'];
                    move_uploaded_file($file_tmp3, $path_v2.$filename3);
                    $file_tmp4 = $_FILES['image_4']['tmp_name'];
                    move_uploaded_file($file_tmp4, $path_v2.$filename4);

                    header('Location: add_product.php?status=success');
                    exit;
                }else{
                    header('Location: add_product.php?status=error');
                    exit;
                }
            }
            else{
                header('Location: add_product.php?status=error');
                exit;
            }
        }else{
            header('Location: add_product.php?status=error');
            exit;
        }
    }else{
        header('Location: add_product.php?status=error');
        exit;
    }

}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin panel</title>
    <link rel="stylesheet" href="assets/scss/style.css"/>
    <link rel="stylesheet" href="assets/scss/add_product.css"/>
</head>
<body>
    <?php include('includes/sidebar.php'); ?>
    <main>
        <?php include('includes/navbar.php'); ?>


        <div class="add_cat_container container">
            <div class="row">
                <div class="card">
                    <div class="card-header formses-header">
                        <h3>Dodaj Zegarek</h4>
                    </div>
                    <div class="card-body row">
                        <form action="/SklepZZegarkami/admin/add_product.php" method="POST" enctype="multipart/form-data">
                            <div class="card_column-1">
                                <label for="">Nazwa</label>
                                <input type="text" name="name" placeholder="Podaj nazwę" class="form-control" required>
                            </div>
                            <div class="card_column-1">
                                <label for="">Marka</label>
                                <input type="text" name="brand" placeholder="Podaj markę" class="form-control" required>
                            </div>
                            <div class="card_column-2">
                                <label for="">Opis</label>
                                <textarea rows="3" name="description" placeholder="Podaj opis" class="form-control" required> </textarea>
                            </div>
                            <div class="card_column-1">
                                <label for="">Mechanizm</label>
                                <input type="text" name="mechanism" placeholder="Jaki mechanizm?" class="form-control" required>
                            </div>
                            <div class="card_column-1">
                                <label for="">Wodoodporność</label>
                                <input type="number" name="waterproof" placeholder="Do ile metrów jest wodoodporny?" class="form-control" min="1" required>
                            </div>
                            <div class="card_column-1">
                                <label for="">Rodzaj koperty</label>
                                <input type="text" name="clasp_type" placeholder="Podaj rodzaj koperty" class="form-control" required>
                            </div>
                            <div class="card_column-1">
                                <label for="">Kolor tarczy</label>
                                <input type="text" name="dial_colour" placeholder="Podaj kolor tarczy" class="form-control" required>
                            </div>
                            <div class="card_column-3">
                                <label for="">Główne zdjęcie</label>
                                <input type="file" name="image_1" class="form-control" required>
                            </div>
                            <div class="card_column-3">
                                <label for="">Drugie</label>
                                <input type="file" name="image_2" class="form-control" required>
                            </div>
                            <div class="card_column-3">
                                <label for="">Trzecie</label>
                                <input type="file" name="image_3" class="form-control" required>
                            </div>
                            <div class="card_column-3">
                                <label for="">Czwarte</label>
                                <input type="file" name="image_4" class="form-control" required>
                            </div>
                            <div class="card_column-4">
                                <label for="">Wybierz kategorie dla zegarka</label>
                                <select id="category-method" size="3" name="selected_category" required>
                                    <?php include('../server/get_watch_category.php'); ?>
                                    <?php while($row = $watch_category->fetch_assoc()){ ?>

                                        <option value="<?php echo $row['category_name']; ?>" ><?php echo $row['category_name']; ?></option>

                                    <?php }?>
                                </select>
                            </div>
                            <div class="card_column-4">
                                <label for="">Ilość</label>
                                <input type="number" name="quantity" placeholder="Podaj ilość" class="quantity-form" min="0" required>
                            </div>
                            <div class="card_column-4">
                                <label for="">Cena</label>
                                <input type="number" name="price" placeholder="Podaj cenę" class="price-form" min="1" required>
                            </div>
                            <div class="card-add_button center">
                                <input type="submit" class="btn" id="add-btn" name="add_product_btn" value="Dodaj przedmiot">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>





    </main>





    <script src="assets/js/main.js"></script>
    <script>
        var urlParams = new URLSearchParams(window.location.search);
        var status = urlParams.get('status');

        if (status === 'success') {
            showPopup('Zegarek został dodany pomyślnie ✔', true);
        } else if (status === 'error'){
            showPopup('Dodawanie zegarka nie powiodło się ❌', false);
        }
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
</body>
</html>
