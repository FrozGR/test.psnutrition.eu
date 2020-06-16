<?php
require_once('init.inc.php');

// Initialise the articles array
$products = [];

// Initialise the category object
$_category = new Category();

// Initialise the title of the page
$title = 'PSN Products';

// Check if there is a category requested
if(isset($_GET['category'])){
    // Try to initialise with ID
    if($_category->setFromId((int) $_GET['category']) && $_category->getStatus() == 1){

        // Display the articles for that category
        $products = $_category->getProducts();

        // Set the page title
        $title = $_category->getName();
    } else {
        // Could not find a category
        $notification = new Notification('The category you requested could not be found.');
        $notification->redirect("products.php");
    }
} else {
    // Display latest products
    $products = $categories;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>PREMIUM SPORTS NUTRITION - <?=$title?></title>
    <?php require_once('include/index.meta.inc.php'); ?>
    <?php require_once('include/styles.inc.php'); ?>
</head>

<body>
<?php require_once('include/nav.inc.php'); ?>
<section class="probootstrap-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12 section-heading probootstrap-animate">
                <h2>PREMIUM SPORTS NUTRITION PRODUCTS</h2>
            </div>
        </div>
        <div class="row">
            <?php
            if(isset($_GET['category'])):
                ?>
                <?php

                // Display the articles for that category
                $products = $_category->getProducts();
                // Set the page title
                $title = $_category->getName();

                foreach($products as $product):
                    ?>
                    <div class="col-md-4 col-sm-6 probootstrap-animate">
                        <div class="probootstrap-card">
                            <div class="probootstrap-card-media">
                                <a href="<?=HOMEPAGE?>product.php?id=<?=htmlentities($product->getId())?>"><img src="img/slider_1.jpg" class="img-responsive img-border" alt="Free HTML5 Template by uicookies.com"></a>
                            </div>
                            <div class="probootstrap-card-text">
                                <h2 class="probootstrap-card-heading mb0"><?=htmlentities($product->getName())?></h2>
                                <p><a href="<?=HOMEPAGE?>product.php?id=<?=htmlentities($product->getId())?>">View more</a></p>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach;
                ?>
            <?php
            else:
                ?>
                <?php
                foreach($products as $product):
                    ?>
                    <div class="col-md-4 col-sm-6 probootstrap-animate">
                        <div class="probootstrap-card">
                            <div class="probootstrap-card-media">
                                <a href="<?=HOMEPAGE?>products.php?category=<?=htmlentities($product['product_category_id'])?>"><img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($product['product_category_image']); ?>" class="img-responsive img-border" alt="<?=htmlentities($product['product_category_name'])?>"></a>
                            </div>
                            <div class="probootstrap-card-text">
                                <h2 class="probootstrap-card-heading mb0"><?=htmlentities($product['product_category_name'])?></h2>
                                <p><a href="<?=HOMEPAGE?>products.php?category=<?=htmlentities($product['product_category_id'])?>">View more</a></p>
                            </div>
                        </div>
                    </div>
                <?php
                endforeach;
                ?>
            <?php
            endif;
            ?>
        </div>
    </div>
</section>
<!-- END section -->
</main>

<?php require_once('include/footer.inc.php'); ?>
<div class="gototop js-top">
    <a href="#" class="js-gotop"><i class="icon-chevron-thin-up"></i></a>
</div>
<script src="js/scripts.min.js"></script>
<script src="js/main.min.js"></script>
<script src="js/custom.js"></script>
</body>
</html>
