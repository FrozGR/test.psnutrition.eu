<?php
    require_once('init.inc.php');
    require_once(ROOT . 'administration' . DS . 'lib' . DS . 'team_manager.class.php');

    // Set up an team manager
    $manager = new TeamManager();
    $currentpage = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>
    <head>
    <?php require_once('include/head.inc.php'); ?>
    <title>PSN Administration - The Team - <?=$manager->getName()?></title>
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
                  <h4 class="card-title"><?=$manager->getName()?></h4>
                </div>
                <div class="card-body">
                <?php
					          if(preg_match('`action=edit&id=`', $currentpage)):
                ?>
                  <?=$manager->getEditForm()?>
                <?php
                  endif;
                ?>
                <?php
					          if(preg_match('`action=new`', $currentpage)):
                ?>
                <?=$manager->getAddForm()?>
                <?php
                  endif;
                ?>
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
