<?php
	require_once('init.inc.php');

	// Get the categories
	$categories = $db->query("
		SELECT *
		FROM product_categories
		ORDER BY product_category_id ASC
	");
?>
<!doctype html>
<html lang="en">

<head>
  <?php require_once('include/head.inc.php'); ?>
  <title>PSN Administration - Categories</title>
</head>

<body>
  <div class="wrapper ">
  <?php require_once('include/nav.inc.php'); ?>
  <div class="content">
  <p><br><a href="category.php?action=new" class="btn">Add Category</a></p>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title ">Categories List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-warning">
                      <th>
                          ID
                        </th>
                        <th>
                          Category Name
                        </th>
                        <th>
                          Status
                        </th>
                        <th class="text-right">
                          Actions
                        </th>
                      </thead>            
                      <?php
                              if(!empty($categories)):
                      ?>
                      <tbody>
                        <?php
                                foreach($categories as $category):
                          ?>
                          <tr>
                              <td><?=htmlentities($category['product_category_id'])?></td>
                              <td><?=htmlentities($category['product_category_name'])?></td>
                              <td><?=($category['product_category_status'] ? 'Active' : 'Inactive')?></td>
                              <td class="td-actions text-right">
                                  <button type="button" rel="tooltip" class="btn btn-primary btn-link btn-sm" onclick="window.location.href = 'category.php?action=edit&id=<?=htmlentities($category['product_category_id'])?>';">
                                      <i class="material-icons">edit</i>
                                  </button>
                                  <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm" onclick="window.location.href = 'category.php?action=delete&id=<?=htmlentities($category['product_category_id'])?>';">
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
                            <td colspan="4" class="text-center">There are no categories to display.</td>
                          </tr>
                        </tbody>
                      <?php
                        endif;
                      ?>
                  </table>
                 </div>
                </div>
              </div>
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