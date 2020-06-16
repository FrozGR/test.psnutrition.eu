<?php
  require_once('init.inc.php');

  // Get the files
  $path    = '../distributors/';
  $files = array_diff(scandir($path), array('.', '..', 'index.html'));
  
  if(isset($_GET['delete'])) {
    unlink($path.$_GET['delete']);
    $notification = new Notification("File was deleted successfully.", 'success');
    $notification->redirect("distributors.php");
} 
?>
<!doctype html>
<html lang="en">

<head>
  <?php require_once('include/head.inc.php'); ?>
  <title>PSN Administration - Distributors</title>
</head>

<body>
  <div class="wrapper ">
  <?php require_once('include/nav.inc.php'); ?>
  <div class="content">
  <p><br>
    <form action="fileupload.php" method="post" enctype="multipart/form-data">
        Upload a New File:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" name="submit" class="btn" value="Upload">
    </form></p>
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-header card-header-warning">
                  <h4 class="card-title ">Files List</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table">
                      <thead class=" text-warning">
                      <th>
                        File Name
                      </th>
                      <th class="text-right">
                        Actions
                      </th>
                      </thead>            
                      <?php
                              if(!empty($files)):
                      ?>
                      <tbody>
                        <?php
                                foreach($files as $file):
                          ?>
                          <tr>
                              <td><a href="../distributors/<?=htmlentities($file)?>" target="_blank"><?=htmlentities($file)?></a></td>
                              <td class="td-actions text-right">
                              <button type="button" rel="tooltip" class="btn btn-danger btn-link btn-sm" onclick="window.location.href = 'distributors.php?delete=<?=htmlentities($file)?>';">
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
                            <td colspan="4" class="text-center">There are no files to display.</td>
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