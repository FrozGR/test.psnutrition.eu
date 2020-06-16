<?php
    require_once('init.inc.php');

	// Get the categories
	$theteam = $db->query("
    SELECT *
    FROM theteam
    ORDER BY member_id ASC
    ");
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
          <h2>The Team</h2>
        </div>
      </div>
      <?php
            if(!empty($theteam)):
        ?>
      <div class="row">
        <?php
            foreach($theteam as $teammember):
        ?>
        <div class="col-md-4 probootstrap-animate">
          <div class="probootstrap-card probootstrap-person text-center">
            <div class="probootstrap-card-media">
              <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($teammember['member_image']); ?>" class="img-responsive" alt="<?=htmlentities($teammember['member_name'])?>">
            </div>
            <div class="probootstrap-card-text">
              <h2 class="probootstrap-card-heading mb0"><?=htmlentities($teammember['member_name'])?></h2>
              <p><small><?=htmlentities($teammember['member_title'])?></small></p>
              <p><?=htmlentities($teammember['member_desc'])?></p>
              <ul class="probootstrap-social">
                <li><a href="<?=htmlentities($teammember['member_facebook'])?>" target="_blank"><i class="icon-facebook2"></i></a></li>
                <li><a href="<?=htmlentities($teammember['member_instagram'])?>" target="_blank"><i class="icon-instagram2"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    <?php
        endforeach;
    ?>
    <?php
        else:
    ?>
    <table>
    <tbody>
        <tr>
            <td colspan="4" class="text-center">There are no team members yet to display.</td>
        </tr>
    </tbody>
    <table>
    <?php
        endif;
    ?>
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
