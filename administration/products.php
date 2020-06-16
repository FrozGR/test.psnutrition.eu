<?php
	require_once('init.inc.php');

	// Get the products
	$products = $db->query("
    SELECT *, product_category_name
    FROM products
    LEFT JOIN product_categories ON (product_category = product_category_id)
    ORDER BY product_id ASC
	");
?>
<!doctype html>
<html lang="en">

<head>
  <?php require_once('include/head.inc.php'); ?>
  <title>PSN Administration - Products</title>
</head>

<body>
  <div class="wrapper ">
  <?php require_once('include/nav.inc.php'); ?>
  <div class="content">
  <p><br><a href="product.php?action=new" class="btn">Add Product</a></p>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title ">Products List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-warning">
                      <th>
                          ID
                        </th>
                        <th>
                          Product Name
                        </th>
                        <th>
                          Product Category
                        </th>
                        <th>
                          Status
                        </th>
                        <th class="text-right">
                          Actions
                        </th>
                      </thead>            
                      <?php
                              if(!empty($products)):
                      ?>
                      <tbody>
                        <?php
                                foreach($products as $product):
                          ?>
                          <tr>
                              <td><?=htmlentities($product['product_id'])?></td>
                              <td><?=htmlentities($product['product_name'])?></td>
                              <td><?=htmlentities($product['product_category_name'])?></td>
                              <td><?=($product['product_status'] ? 'Active' : 'Inactive')?></td>
                              <td class="td-actions text-right">
                                  <button type="button" rel="tooltip" class="btn btn-primary btn-link btn-sm" onclick="window.location.href = 'product.php?action=edit&id=<?=htmlentities($product['product_id'])?>';">
                                      <i class="material-icons">edit</i>
                                  </button>
                                  <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm" onclick="window.location.href = 'product.php?action=delete&id=<?=htmlentities($product['product_id'])?>';">
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