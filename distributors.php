<?php
    require_once('init.inc.php');

    // Get the files
    $path    = 'distributors/';
    $files = array_diff(scandir($path), array('.', '..', 'index.html'));
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
          <h2>Distributors Private Area</h2>
        </div>
      </div>
      <?php
            if(!empty($files)):
        ?>
      <div class="row">
      <table>
        <tbody>
        <tr>
            <th>Files Available:</th>
        </tr>
            <?php
                foreach($files as $file):
            ?> 
            <tr>
                <td colspan="4" class="left"><a href="distributors/<?=htmlentities($file)?>" target="_blank"><?=htmlentities($file)?></a></td>
            
            </tr>
            <?php
                endforeach;
            ?>
        </tbody>
      </table>
    <?php
        else:
    ?>
    <table>
    <tbody>
        <tr>
            <td colspan="4" class="text-center">There are no files available yet to display.</td>
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
