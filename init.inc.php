<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the configuration file
require_once('config.inc.php');
require_once(ROOT . 'lib' . DS . 'exception.class.php');
require_once(ROOT . 'lib' . DS . 'notification.class.php');
require_once(ROOT . 'lib' . DS . 'database.class.php');
require_once(ROOT . 'lib' . DS . 'user.class.php');
require_once(ROOT . 'lib' . DS . 'product.class.php');
require_once(ROOT . 'lib' . DS . 'team.class.php');
require_once(ROOT . 'lib' . DS . 'category.class.php');

// Initialise a new connection with the database
$db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Initialise an array of notifications to show to the user
$notifications = [];

// Check if there are any notifications in the session
if(isset($_SESSION['notifications']) && !empty($_SESSION['notifications']) && is_array($_SESSION['notifications'])){
    // Iterate through all the notifications in the session
    foreach($_SESSION['notifications'] as $notification){
        // Append a new notification to the array
        $notifications[] = new Notification($notification[0], $notification[1]);
    }

    // Unset the notifications from the session
    unset($_SESSION['notifications']);
}

// Set a new User object for the current user
$current_user = new User();

// Get authorisation for the user
$current_user->authorise();

// Get all news categories
$categories = $db->query("SELECT product_category_id, product_category_name, product_category_image FROM product_categories WHERE product_category_status = 1");