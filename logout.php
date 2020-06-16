<?php
    require_once('init.inc.php');
    require_once(ROOT . 'lib' . DS . 'authentication.class.php');

    // Handle user authentication
    $authentication = new Authentication();

    // Logout
    $authentication->logout();