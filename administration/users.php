<?php
	require_once('init.inc.php');

	// Get the users
	$users = $db->query("
		SELECT *
		FROM users
		ORDER BY user_id ASC
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
      <p><br><a href="user.php?action=new" class="btn">Add User</a></p>
      <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title ">Users List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-warning">
                      <th><b>ID</b></th>
                      <th><b>Name</b></th>
                      <th><b>Email</b></th>
                      <th><b>Is Admin</b></th>
                      <th><b>Status</b></th>
                      <th class="text-right"><b>Actions</b></th>
                      </thead>
                      <?php
                      if(!empty($users)):
                        ?>
                      <tbody>
                        <?php
                        foreach($users as $user):
                      ?>
                          <tr>
                              <td><?=htmlentities($user['user_id'])?></td>
                              <td><?=htmlentities($user['user_name'])?></td>
                              <td><?=htmlentities($user['user_email'])?></td>
                              <td><?=($user['user_is_admin'] ? 'Yes' : 'No')?></td>
                              <td><?=($user['user_status'] ? 'Active' : 'Inactive')?></td>
                              <td class="td-actions text-right">
                                  <button type="button" rel="tooltip" class="btn btn-primary btn-link btn-sm" onclick="window.location.href = 'user.php?action=edit&id=<?=htmlentities($user['user_id'])?>';">
                                      <i class="material-icons">edit</i>
                                  </button>
                                  <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm" onclick="window.location.href = 'user.php?action=delete&id=<?=htmlentities($user['user_id'])?>';">
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
                            <td colspan="4" class="text-center">There are no users to display.</td>
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