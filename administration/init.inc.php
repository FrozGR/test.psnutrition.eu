<?php
// Include the main initialisation script
require_once('../init.inc.php');
	
// Check if user is an admin
if(!$current_user->isAdmin()){
    // Redirect them to the homepage
    $notification = new Notification("You do not have access to that page.");
    $notification->redirect(HOMEPAGE);
}