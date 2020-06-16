<?php
    require_once('init.inc.php');
    require_once(ROOT . 'administration' . DS . 'lib' . DS . 'user_manager.class.php');

    // Set up an user manager
    $manager = new UserManager();
?>
<!DOCTYPE html>
<html>
    <head>
    <?php require_once('include/head.inc.php'); ?>
    <title>PSN Administration - Users - <?=$manager->getTitle()?></title>
    </head>
    <body>
    <div class="wrapper ">
    <?php require_once('include/nav.inc.php'); ?>
    <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title"><?=$manager->getTitle()?></h4>
                </div>
                <div class="card-body">
                  <?=$manager->getForm()?>
                </div>
              </div>
        </div>
      </div>

    <?php require_once('include/footer.inc.php'); ?>
  </div>
  </div>

  <?php require_once('include/scripts.inc.php'); ?>
  </body>
</html>
