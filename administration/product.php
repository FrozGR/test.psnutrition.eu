<?php
    require_once('init.inc.php');
    require_once(ROOT . 'administration' . DS . 'lib' . DS . 'product_manager.class.php');

    // Set up an category manager
    $manager = new ProductManager();

    $images = "";
    $currentpage = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html>
    <head>
    <?php require_once('include/head.inc.php'); ?>
    <title>PSN Administration - Categories - <?=$manager->getName()?></title>
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
      <div class="content">
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title">Product Images</h4>
                </div>
                <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-warning">
                      <th>
                          ID
                        </th>
                        <th>
                          Product Image
                        </th>
                        <th class="text-right">
                          Actions
                        </th>
                      </thead>            
                      <?php
                          if(preg_match('`action=edit&id=`', $currentpage)):
                      ?>
                      <tbody>
                        <?php
                                foreach($images as $image):
                          ?>
                          <tr>
                              <td><?=htmlentities($image['product_image_id'])?></td>
                              <td><?=htmlentities($product['product_image'])?></td>
                              <td class="td-actions text-right">
                                  <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm" onclick="window.location.href = 'product.php?action=delete&id=<?=htmlentities($image['product_image_id'])?>';">
                                      <i class="material-icons">close</i>
                                  </button>
                              </td>
                          </tr>
                        <?php
                              endforeach;
                          ?>
                      </tbody>
                      <?php
                          else:
                        ?>
                        <tbody>
                          <tr>
                            <td colspan="4" class="text-center">There are no products to display.</td>
                          </tr>
                        </tbody>
                      <?php
                        endif;
                      ?>
                  </table>
                      

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
  <?php require_once('include/scripts.inc.php'); ?>
  </body>
</html>
