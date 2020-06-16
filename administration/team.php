<?php
	require_once('init.inc.php');

	// Get the categories
	$theteam = $db->query("
		SELECT *
		FROM theteam
		ORDER BY member_id ASC
	");
?>
<!doctype html>
<html lang="en">

<head>
  <?php require_once('include/head.inc.php'); ?>
  <title>PSN Administration - The Team</title>
</head>

<body>
  <div class="wrapper ">
  <?php require_once('include/nav.inc.php'); ?>
  <div class="content">
  <p><br><a href="teammember.php?action=new" class="btn">Add New Team Member</a></p>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title ">The Team Members List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-warning">
                      <th>
                          ID
                        </th>
                        <th>
                          Member Name
                        </th>
                        <th>
                          Member Status
                        </th>
                        <th class="text-right">
                          Actions
                        </th>
                      </thead>            
                      <?php
                              if(!empty($theteam)):
                      ?>
                      <tbody>
                        <?php
                                foreach($theteam as $teammember):
                          ?>
                          <tr>
                              <td><?=htmlentities($teammember['member_id'])?></td>
                              <td><?=htmlentities($teammember['member_name'])?></td>
                              <td><?=($teammember['member_status'] ? 'Active' : 'Inactive')?></td>
                              <td class="td-actions text-right">
                                  <button type="button" rel="tooltip" class="btn btn-primary btn-link btn-sm" onclick="window.location.href = 'teammember.php?action=edit&id=<?=htmlentities($teammember['member_id'])?>';">
                                      <i class="material-icons">edit</i>
                                  </button>
                                  <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm" onclick="window.location.href = 'teammember.php?action=delete&id=<?=htmlentities($teammember['member_id'])?>';">
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
                            <td colspan="4" class="text-center">There are no team members to display.</td>
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