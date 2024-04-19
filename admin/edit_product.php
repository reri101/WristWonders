<?php

session_start();
include('../server/connection.php');

if (isset($_GET['edit'])) {
    $product_id = $_GET['edit'];

} else {
    echo "Brak ID produktu do edycji.";
}

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

if(isset($_POST['edit_product_btn'])){
    $name = $_POST['name'];
    $brand = $_POST['brand'];
    $mechanism = $_POST['mechanism'];
    $description = $_POST['description'];
    $waterproof = $_POST['waterproof'];
    $clasp_type = $_POST['clasp_type'];
    $dial_colour = $_POST['dial_colour'];
    $image_1 = $_FILES['image_1']['name'];
    $image_2 = $_FILES['image_2']['name'];
    $image_3 = $_FILES['image_3']['name'];
    $image_4 = $_FILES['image_4']['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $product_id = $product_id;

    // uzupelnianie danymi z bazy
    $stmt_one_watch = $connection->prepare("SELECT product.*, watch.*, category.category_name as category_name
                                            FROM product 
                                            INNER JOIN watch ON product.watch_ID = watch.watch_ID
                                            INNER JOIN watch_category ON product.watch_ID = watch_category.watch_ID 
                                            INNER JOIN category ON watch_category.category_ID = category.category_ID 
                                            WHERE product.product_ID = ?
                                            LIMIT 1");

    $stmt_one_watch->bind_param("i",$product_id);
    $stmt_one_watch->execute();

    $watch = $stmt_one_watch->get_result();
    $stmt_one_watch->close();
    $old_infos = $watch->fetch_assoc();

    if($name=="")
        $name=$old_infos['name'];
    if($brand=="")
        $brand = $old_infos['brand'];
    if($mechanism=="")
        $mechanism=$old_infos['mechanism'];
    if($description=="")
        $description=$old_infos['description'];
    if($waterproof=="")
        $waterproof=$old_infos['waterproof'];
    if($clasp_type=="")
        $clasp_type=$old_infos['clasp_type'];
    if($dial_colour=="")
        $dial_colour=$old_infos['dial_Colour'];
    if($image_1=="")
        $image_1=$old_infos['image_first'];
    if($image_2=="")
        $image_2=$old_infos['image_second'];
    if($image_3=="")
        $image_3=$old_infos['image_third'];
    if($image_4=="")
        $image_4=$old_infos['image_fourth'];
    if($quantity=="")
        $quantity=$old_infos['availability'];
    if($price=="")
        $price=$old_infos['price'];
    if(empty($_POST['selected_category'])){
        $stmt_category1 = $connection->prepare("SELECT * FROM category WHERE category_name = ? LIMIT 1");
        $stmt_category1->bind_param('s',$old_infos['category_name']);
        if($stmt_category1->execute()){
            $category1 = $stmt_category1->get_result();
            $stmt_category1->close();
            $tmp_cat1 = $category1->fetch_assoc();

            $selected_category=$tmp_cat1['category_ID'];
            $category_name=$tmp_cat1['category_name'];
        }
    }else{
        $selected_category = $_POST['selected_category'];
        $stmt_category1 = $connection->prepare("SELECT * FROM category WHERE category_name = ? LIMIT 1");
        $stmt_category1->bind_param('s',$selected_category);
        if($stmt_category1->execute()){
            $category1 = $stmt_category1->get_result();
            $stmt_category1->close();
            $tmp_cat1 = $category1->fetch_assoc();

            $selected_category = $tmp_cat1['category_ID'];
            $category_name = $tmp_cat1['category_name'];
        }
    }

    $watch_id=$old_infos['watch_ID'];
        

    //

    $path="/assets/imgs/zegarki/".$category_name."/";

    if(!empty($_FILES['image_1']['name'])){
        $image_extension1 = pathinfo($image_1, PATHINFO_EXTENSION);
        $filename1 = time().'_1.'.$image_extension1;
        $file_path1 = $path.$filename1;
    }else{
        $file_path1 = $image_1; 
    }
    if(!empty($_FILES['image_2']['name'])){
        $image_extension2 = pathinfo($image_2, PATHINFO_EXTENSION);
        $filename2 = time().'_2.'.$image_extension2;
        $file_path2 = $path.$filename2;
    }else{
        $file_path2 = $image_2; 
    }
    if(!empty($_FILES['image_3']['name'])){
        $image_extension3 = pathinfo($image_3, PATHINFO_EXTENSION);
        $filename3 = time().'_3.'.$image_extension3;
        $file_path3 = $path.$filename3;
    }else{
        $file_path3 = $image_3; 
    }
    if(!empty($_FILES['image_4']['name'])){
        $image_extension4 = pathinfo($image_4, PATHINFO_EXTENSION);
        $filename4 = time().'_4.'.$image_extension4;
        $file_path4 = $path.$filename4;
    }else{
        $file_path4 = $image_4; 
    }

    $stmt_watch = $connection->prepare("UPDATE watch SET `name`=?, mechanism=?, waterproof=?, clasp_type=?, dial_Colour=?, `description`=?, brand=? WHERE watch_ID=?");
    $stmt_watch->bind_param('sssssssi', $name, $mechanism, $waterproof, $clasp_type, $dial_colour, $description, $brand,$watch_id);
    if($stmt_watch->execute()){
        $stmt_watch->close();


        $stmt_product = $connection->prepare("UPDATE product SET price=?, `availability`=?, image_first=?, image_second=?, image_third=?, image_fourth=? WHERE watch_ID=?");
        $stmt_product->bind_param('iissssi', $price, $quantity, $file_path1, $file_path2, $file_path3, $file_path4,$watch_id);
        if($stmt_product->execute()){
            $stmt_product->close();

            $stmt_add_to_cat = $connection->prepare("UPDATE watch_category SET category_ID=? WHERE watch_ID=?");
            $stmt_add_to_cat->bind_param('ii', $selected_category, $watch_id);
            if($stmt_add_to_cat->execute()){
                $stmt_add_to_cat->close();

                $path_v2 = "..".$path;
                if(!empty($_FILES['image_1']['name'])){
                    $file_tmp = $_FILES['image_1']['tmp_name'];
                    move_uploaded_file($file_tmp, $path_v2.$filename1);
                }
                if(!empty($_FILES['image_2']['name'])){
                    $file_tmp2 = $_FILES['image_2']['tmp_name'];
                    move_uploaded_file($file_tmp2, $path_v2.$filename2);
                }
                if(!empty($_FILES['image_3']['name'])){
                    $file_tmp3 = $_FILES['image_3']['tmp_name'];
                    move_uploaded_file($file_tmp3, $path_v2.$filename3);
                }
                if(!empty($_FILES['image_4']['name'])){
                    $file_tmp4 = $_FILES['image_4']['tmp_name'];
                    move_uploaded_file($file_tmp4, $path_v2.$filename4);
                }

                header('Location: all_products.php?status=success_edit');
                exit;
            }else{
                header('Location: edit_product.php?status=error');
                exit;
            }
        }else{
            header('Location: edit_product.php?status=error');
            exit;
        }
    }else{
        header('Location: edit_product.php?status=error');
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
                        <h3>Edytuj Zegarek</h4>
                    </div>
                    <div class="card-body row">
                        <form action="/SklepZZegarkami/admin/edit_product.php?edit=<?php echo $product_id; ?>" method="POST" enctype="multipart/form-data">
                            <div class="card_column-1">
                                <label for="">Nazwa</label>
                                <input type="text" name="name" placeholder="Podaj nazwę" class="form-control">
                            </div>
                            <div class="card_column-1">
                                <label for="">Marka</label>
                                <input type="text" name="brand" placeholder="Podaj markę" class="form-control">
                            </div>
                            <div class="card_column-2">
                                <label for="">Opis</label>
                                <textarea rows="3" name="mechanism" placeholder="Podaj opis" class="form-control"> </textarea>
                            </div>
                            <div class="card_column-1">
                                <label for="">Mechanizm</label>
                                <input type="text" name="description" placeholder="Jaki mechanizm?" class="form-control">
                            </div>
                            <div class="card_column-1">
                                <label for="">Wodoodporność</label>
                                <input type="number" name="waterproof" placeholder="Do ile metrów jest wodoodporny?" min="1" class="form-control">
                            </div>
                            <div class="card_column-1">
                                <label for="">Rodzaj koperty</label>
                                <input type="text" name="clasp_type" placeholder="Podaj rodzaj koperty" class="form-control">
                            </div>
                            <div class="card_column-1">
                                <label for="">Kolor tarczy</label>
                                <input type="text" name="dial_colour" placeholder="Podaj kolor tarczy" class="form-control">
                            </div>
                            <div class="card_column-3">
                                <label for="">Główne zdjęcie</label>
                                <input type="file" name="image_1" class="form-control">
                            </div>
                            <div class="card_column-3">
                                <label for="">Drugie</label>
                                <input type="file" name="image_2" class="form-control">
                            </div>
                            <div class="card_column-3">
                                <label for="">Trzecie</label>
                                <input type="file" name="image_3" class="form-control">
                            </div>
                            <div class="card_column-3">
                                <label for="">Czwarte</label>
                                <input type="file" name="image_4" class="form-control" >
                            </div>
                            <div class="card_column-4">
                                <label for="">Wybierz kategorie dla zegarka</label>
                                <select id="category-method" size="3" name="selected_category" >
                                    <?php include('../server/get_watch_category.php'); ?>
                                    <?php while($row = $watch_category->fetch_assoc()){ ?>

                                        <option value="<?php echo $row['category_name']; ?>" ><?php echo $row['category_name']; ?></option>

                                    <?php }?>
                                </select>
                            </div>
                            <div class="card_column-4">
                                <label for="">Ilość</label>
                                <input type="number" name="quantity" placeholder="Podaj ilość" class="quantity-form" min="0">
                            </div>
                            <div class="card_column-4">
                                <label for="">Cena</label>
                                <input type="number" name="price" placeholder="Podaj cenę" class="price-form" min="1">
                            </div>
                            <div class="card-add_button center">
                                <input type="submit" class="btn" id="add-btn" name="edit_product_btn" value="Edytuj przedmiot">
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
            showPopup('Zegarek został zedytowany pomyślnie ✔', true);
        } else if (status === 'error'){
            showPopup('Edytowanie zegarka nie powiodło się ❌', false);
        }
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.15.4/js/all.js" integrity="sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc" crossorigin="anonymous"></script>
</body>
</html>
