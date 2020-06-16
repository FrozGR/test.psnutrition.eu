<?php
    require_once('init.inc.php');
    require_once(ROOT . 'lib' . DS . 'registration.class.php');
	
	// Check if user is already logged in
	if($current_user->getId()){
		// Redirect them to the homepage
		$notification = new Notification("You are already logged in.");
		$notification->redirect(HOMEPAGE);
	}

    // Handle registration form input
    $registration = new Registration();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once(ROOT . 'includes' . DS . 'meta.inc.php'); ?>
		<title>Northampton News - Register</title>
	</head>
	<body>
        <?php require_once(ROOT . 'includes' . DS . 'header.inc.php'); ?>

        <?php require_once(ROOT . 'includes' . DS . 'nav.inc.php'); ?>
		
		<main>

			<article>
				<h2>User Registration</h2>
				<p>Fill out the form below to register for a new account.</p>
				<form method="POST">
					<p>Note: All fields with an asterisk (*) are required.</p>

					<label>Full Name*</label> <input type="text" name="full_name" max="64" value="<?=@$_POST['full_name']?>" />
					<label>Email*</label> <input type="text" name="email" max="64" value="<?=@$_POST['email']?>" />
					<label>Password*</label> <input type="password" name="password" max="256" />
					<label>Re-type Password*</label> <input type="password" name="password_again" max="256" />
                    
					<input type="submit" name="register" value="Register" />
				</form>
			</article>
		</main>

        <?php require_once(ROOT . 'includes' . DS . 'footer.inc.php'); ?>

	</body>
</html>
