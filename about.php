<?php
    require_once('init.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>PSNUTRITION.eu - Distributors</title>
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
					if(!empty($categories)):
        ?>
        <?php
							foreach($categories as $category):
						?>
        <div class="col-md-4 col-sm-6 probootstrap-animate">
          <div class="probootstrap-card">
            <div class="probootstrap-card-media">
              <a href="<?=HOMEPAGE?>categories.php?category=<?=htmlentities($category['category_id'])?>"><img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($category['category_image']); ?>" class="img-responsive img-border" alt="<?=htmlentities($category['category_name'])?>"></a>
            </div>
            <div class="probootstrap-card-text">
              <h2 class="probootstrap-card-heading mb0"><?=htmlentities($category['category_name'])?></h2>
              <p><a href="<?=HOMEPAGE?>categories.php?category=<?=htmlentities($category['category_id'])?>">View more</a></p>
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
