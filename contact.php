<?php
    require_once('init.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>PSNUTRITION.eu - Contact</title>
    <?php require_once('include/index.meta.inc.php'); ?>
    <?php require_once('include/styles.inc.php'); ?>
  </head>

  <body>
	  <?php require_once('include/nav.inc.php'); ?>
    <section class="probootstrap-section">
    <div class="container">
      <div class="row">
        <div class="col-md-8">
          <form action="#" method="post" class="probootstrap-form mb60">
            <div class="row">
            <h2>Contact us</h2>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="fname">First Name</label>
                  <input type="text" class="form-control" id="fname" name="fname">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="lname">Last Name</label>
                  <input type="text" class="form-control" id="lname" name="lname">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="telephone">Telephone</label>
              <input type="telephone" class="form-control" id="telephone" name="telephone">
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea cols="30" rows="10" class="form-control" id="message" name="message"></textarea>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" id="submit" name="submit" value="Send Message">
            </div>
          </form>
        </div>
        <div class="col-md-3 col-md-push-1">
          <h4>Contact Details</h4>
          <ul class="with-icon colored">
            <li><i class="icon-mail"></i><span>info@psnutrition.eu</span></li>
            <li><i class="icon-phone2"></i><span>+123 456 7890</span></li>
          </ul>
        </div>
      </div>
    </div>
  </section>  
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
