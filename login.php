<?php
    require_once('init.inc.php');
	require_once(ROOT . 'lib' . DS . 'authentication.class.php');
	
	// Check if user is already logged in
	if($current_user->getId()){
		// Redirect them to the homepage
		$notification = new Notification("You are already logged in.");
		$notification->redirect(HOMEPAGE);
	}

    // Handle user authentication
    $authentication = new Authentication();
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
	  <section class="page-title">
		<div class="container">
		<!-- <div class="row">
			<div class="col-md-12 section-heading"> -->
			<h1>Distributor Login</h1>
			<!-- </div>
		</div> -->
		</div>
  </section>
	  <main>
			<div class="container">
				<div class="probootstrap-cta probootstrap-animate">
				<h2 class="mb20">Enter your credentials in the form below to login to the system.</h2>
				<form method="POST">
					<label>Email</label> <input type="text" style="color: #000000;" name="email" max="64" value="<?=@$_POST['email']?>" />
					<label>Password</label> <input type="password" style="color: #000000;" name="password" max="256" />
                    
					<input type="submit" name="login" value="Login" role="button" class="btn btn-primary"/>
				</form>
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
    </body>
</html>
