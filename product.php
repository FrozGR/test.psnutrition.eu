<?php
	require_once('init.inc.php');

	// Show specific product
    $product = new Product();
    
    // Initialise the comments array
	$images = [];
    
    // Initialise the comments array
    $comments = [];

	if(isset($_GET['id']) && $product->setFromId($_GET['id']) && $product->getStatus() == 1){
        $prod_id = $_GET['id'];
		// Set the page title
        $title = $product->getName();
        
        // Get the product images
        $images = $db->query("
            SELECT *
            FROM product_images
            WHERE product_image_parent = ".$prod_id."
                  AND product_image_status = 1
            ORDER BY product_image_id ASC
        ");
        
        // Get the comments
        $comments = $product->getComments();

	} else {
		// Add a notification to the session and redirect the user to the products list
		$notification = new Notification("The product you requested was not found.");
		$notification->redirect("products.php");
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>PSNUTRITION.eu - Distributors</title>
    <?php require_once('include/index.meta.inc.php'); ?>
    <?php require_once('include/styles.inc.php'); ?>
    <link rel="stylesheet" href="css/product.css">
  </head>

  <body>
	  <?php require_once('include/nav.inc.php'); ?>
      <section class="page-title">
        <div class="container">
        <div class="grid second-nav">
        <div class="column-xs-12">
            <nav>
            <ol class="breadcrumb-list">
                <li class="breadcrumb-item"><a href="<?=HOMEPAGE?>products.php">Home</a></li>
                <li class="breadcrumb-item"><a href="products.php?category=<?=htmlentities($product->getCategory()->getId())?>"><?=htmlentities($product->getCategory()->getName())?></a></li>
                <li class="breadcrumb-item active"><?=htmlentities($product->getName())?></li>
            </ol>
            </nav>
        </div>
        </div>
    </section>

    <main>
        <div class="grid product">
            <div class="column-xs-12 column-md-7">
                <div class="product-gallery">
                <div class="product-image">
                <img class="active" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($images[0]['product_image']); ?>" alt="<?=htmlentities($title)?>">
                </div>
                <ul class="image-list">
                    <?php
                        foreach($images as $image):
                    ?>
                        <li class="image-item"><img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($image['product_image']); ?>" alt="<?=htmlentities($title)?>"></li>
                    <?php
                        endforeach;
                    ?>    
                </ul>
                </div>
            </div>
            <div class="column-xs-12 column-md-5">
                <h1><?=htmlentities($product->getName())?></h1>
                <div class="description">
                <p><?=nl2br($product->getDesc())?></p>
                </div>
            </div>
            </div>
            <div class="grid related-products">
            </div>
        </div>
        </main>

      <?php require_once('include/footer.inc.php'); ?>
        <div class="gototop js-top">
        <a href="#" class="js-gotop"><i class="icon-chevron-thin-up"></i></a>
        </div>
    <script src="js/scripts.min.js"></script>
    <script src="js/main.min.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/product.js"></script>
    </body>
</html>
