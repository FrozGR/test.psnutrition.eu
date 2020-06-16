<?php 
      $currentpage = $_SERVER['REQUEST_URI'];
      $pageName = "-" ;
    ?>
  <!-- START: header -->

<div class="sidebar" data-color="orange" data-background-color="white">
      <!--
      Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

      Tip 2: you can also add an image using data-image tag
  -->
      <div class="logo">
        <a href="<?=ADMINISTRATION?>" class="simple-text logo-normal">
          PSN Administration
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
        <?php
            if($currentpage=="/administration/" || $currentpage=="/administration/index.php"):
          ?>
          <?php
              $pageName="Dashboard"
          ?>
          <li class="nav-item active  ">
            <a class="nav-link" href="<?=ADMINISTRATION?>">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <?php
				      	else:
			  	  ?>  
          <li class="nav-item ">
            <a class="nav-link" href="<?=ADMINISTRATION?>">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <?php
                endif;
          ?>
          <?php
            if(preg_match('`settings.php`', $currentpage)):
          ?>
          <?php
              $pageName="Settings Section";
          ?>
          <li class="nav-item active  ">
            <a class="nav-link" href="<?=ADMINISTRATION?>settings.php">
              <i class="material-icons">home</i>
              <p>Settings</p>
            </a>
          </li>
          <?php
				      	else:
			  	  ?>  
          <li class="nav-item ">
            <a class="nav-link" href="<?=ADMINISTRATION?>settings.php">
              <i class="material-icons">home</i>
              <p>Settings</p>
            </a>
          </li>
          <?php
                endif;
              ?>
          <?php
            if(preg_match('`categories.php`', $currentpage) || preg_match('`category.php`', $currentpage)):
          ?>
          <?php
              $pageName="Categories Section"
          ?>
          <li class="nav-item active  ">
            <a class="nav-link" href="<?=ADMINISTRATION?>categories.php">
              <i class="material-icons">menu</i>
              <p>Categories</p>
            </a>
          </li>
          <?php
				      	else:
			  	  ?>  
          <li class="nav-item ">
            <a class="nav-link" href="<?=ADMINISTRATION?>categories.php">
              <i class="material-icons">menu</i>
              <p>Categories</p>
            </a>
          </li>
          <?php
                endif;
          ?>
        <?php
            if(preg_match('`products.php`', $currentpage) || preg_match('`product.php`', $currentpage)):
          ?>
          <?php
              $pageName="Products Section"
          ?>
          <li class="nav-item active  ">
            <a class="nav-link" href="<?=ADMINISTRATION?>products.php">
              <i class="material-icons">content_paste</i>
              <p>Products</p>
            </a>
          </li>
          <?php
				      	else:
			  	  ?>  
          <li class="nav-item ">
            <a class="nav-link" href="<?=ADMINISTRATION?>products.php">
              <i class="material-icons">content_paste</i>
              <p>Products</p>
            </a>
          </li>
          <?php
                endif;
              ?>
          <?php
            if(preg_match('`users.php`', $currentpage) || preg_match('`user.php`', $currentpage)):
          ?>
          <?php
              $pageName="Users Section";
          ?>
          <li class="nav-item active  ">
            <a class="nav-link" href="<?=ADMINISTRATION?>users.php">
              <i class="material-icons">person</i>
              <p>Users</p>
            </a>
          </li>
          <?php
				      	else:
			  	  ?>  
          <li class="nav-item ">
            <a class="nav-link" href="<?=ADMINISTRATION?>users.php">
              <i class="material-icons">person</i>
              <p>Users</p>
            </a>
          </li>
          <?php
                endif;
              ?>
          <?php
            if(preg_match('`distributors.php`', $currentpage)):
          ?>
          <?php
              $pageName="Distributors Section"
          ?>
          <li class="nav-item active  ">
            <a class="nav-link" href="<?=ADMINISTRATION?>distributors.php">
              <i class="material-icons">cloud_queue</i>
              <p>Distributors (Private)</p>
            </a>
          </li>
          <?php
				      	else:
			  	  ?>  
          <li class="nav-item ">
            <a class="nav-link" href="<?=ADMINISTRATION?>distributors.php">
              <i class="material-icons">cloud_queue</i>
              <p>Distributors (Private)</p>
            </a>
          </li>
          <?php
              endif;
            ?>
          <?php
            if(preg_match('`team.php`', $currentpage) || preg_match('`teammember.php`', $currentpage)):
          ?>
          <?php
              $pageName="The Team Section"
          ?>
          <li class="nav-item active  ">
            <a class="nav-link" href="<?=ADMINISTRATION?>team.php">
              <i class="material-icons">people_alt</i>
              <p>The Team</p>
            </a>
          </li>
          <?php
				      	else:
			  	  ?>  
          <li class="nav-item ">
            <a class="nav-link" href="<?=ADMINISTRATION?>team.php">
              <i class="material-icons">people_alt</i>
              <p>The Team</p>
            </a>
          </li>
          <?php
                endif;
            ?>
          <li class="nav-item active-pro ">
            <a class="nav-link" href="<?=HOMEPAGE?>logout.php">
              <i class="material-icons">exit_to_app</i>
              <p>Log out</p>
            </a>
          </li>
          <!-- your sidebar here -->
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;"><?=htmlentities($pageName)?></a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Account
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <a class="dropdown-item" href="user.php?action=edit&id=<?=htmlentities($current_user->getId())?>">Profile Details</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?=HOMEPAGE?>">Check Site</a>
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="<?=HOMEPAGE?>logout.php">Log out</a>
                </div>
              </li>
              <!-- your navbar here -->
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->


  <?php require_once(ROOT . 'include' . DS . 'notifications.inc.php'); ?>