<?php 
      $currentpage = $_SERVER['REQUEST_URI'];
    ?>
  <!-- START: header -->
  
  <div class="probootstrap-loader"></div>

  <header role="banner" class="probootstrap-header">
    <div class="container">
        <a href="<?=HOMEPAGE?>" class="probootstrap-logo">PSN<!-- <img src="img/logo.png"/ width="5%" height="5%">--></a>
        
        <a href="#" class="probootstrap-burger-menu visible-xs" ><i>Menu</i></a>
        <div class="mobile-menu-overlay"></div>

        <nav role="navigation" class="probootstrap-nav hidden-xs">
          <ul class="probootstrap-main-nav">
          <?php
            if($currentpage=="/" || $currentpage==""):
              ?>
            <li class="active"><a href="<?=HOMEPAGE?>">Home</a></li>
            <?php
				      	else:
			  	  ?>
            <li><a href="<?=HOMEPAGE?>">Home</a></li>
            <?php
                  endif;
                ?>
            <?php
					      if(preg_match('`products.php`', $currentpage) || preg_match('`product.php`', $currentpage)):
            ?>
            <li class="active dropdown"><a class="nav-link dropdown-toggle" href="<?=HOMEPAGE?>products.php" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Products</a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <?php
                    foreach($categories as $category):
                  ?>
                  <li><a class="dropdown-item" href="<?=HOMEPAGE?>products.php?category=<?=htmlentities($category['product_category_id'])?>"><?=htmlentities($category['product_category_name'])?></a></li>
                  <?php
                      endforeach;
                  ?>
				    	</ul>
            </li>
            <?php
				      	else:
			  	  ?>
            <li class="dropdown"><a class="nav-link dropdown-toggle" href="<?=HOMEPAGE?>products.php" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Products</a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <?php
                    foreach($categories as $category):
                  ?>
                  <li><a class="dropdown-item" href="<?=HOMEPAGE?>products.php?category=<?=htmlentities($category['product_category_id'])?>"><?=htmlentities($category['product_category_name'])?></a></li>
                  <?php
                      endforeach;
                  ?>
				    	</ul>
            </li>
            <?php
                endif;
            ?>
            <?php
               if(preg_match('`team.php`', $currentpage)):
            ?>
            <li class="active"><a href="<?=HOMEPAGE?>team.php">The Team</a></li>
            <?php
				      	else:
			  	  ?>
            <li><a href="<?=HOMEPAGE?>team.php">The Team</a></li>
            <?php
                  endif;
            ?>
            <?php
						  if(!$current_user->getId() && preg_match('`login.php`', $currentpage)):
						?>
              <li class="active"><a href="<?=HOMEPAGE?>login.php">Distributors</a></li>
            <?php
              endif;
            ?>
            <?php
						  if(!$current_user->getId() && !preg_match('`login.php`', $currentpage)):
						?>
              <li><a href="<?=HOMEPAGE?>login.php">Distributors</a></li>
            <?php
              endif;
            ?>
            <?php
						  if($current_user->getId() && preg_match('`distributors.php`', $currentpage)):
						?>
              <li class="active"><a href="<?=HOMEPAGE?>distributors.php">Distributors</a></li>
            <?php
              endif;
            ?>
            <?php
						  if($current_user->getId() && !preg_match('`distributors.php`', $currentpage)):
						?>
              <li><a href="<?=HOMEPAGE?>distributors.php">Distributors</a></li>
            <?php
                    endif;
            ?>
            <?php
               if(preg_match('`about.php`', $currentpage)):
            ?>
            <li class="active"><a href="<?=HOMEPAGE?>about.php">About</a></li>
            <?php
				      	else:
			  	  ?>
            <li><a href="<?=HOMEPAGE?>about.php">About</a></li>
            <?php
                  endif;
            ?>
            <?php
               if(preg_match('`contact.php`', $currentpage)):
            ?>
            <li class="active"><a href="<?=HOMEPAGE?>contact.php">Contact</a></li>
            <?php
				      	else:
			  	  ?>
            <li><a href="<?=HOMEPAGE?>contact.php">Contact</a></li>
            <?php
                  endif;
            ?>
            <?php
              if($current_user->isAdmin()):
						?>
              <li><a href="<?=ADMINISTRATION?>">Administration</a></li>
            <?php
							endif;
						?>
            <?php
						  if($current_user->getId() || $current_user->isAdmin()):
						?>
              <li><a href="<?=HOMEPAGE?>logout.php">Logout</a></li>
            <?php
              endif;
            ?>
          </ul>
          <ul class="probootstrap-right-nav hidden-xs">
            <li><a href="#"><i class="icon-twitter"></i></a></li>
            <li><a href="https://www.facebook.com/Premium-Sports-Nutrition-118799916173348/" target="_blank"><i class="icon-facebook2"></i></a></li>
            <li><a href="#"><i class="icon-instagram2"></i></a></li>
          </ul>
          <div class="extra-text visible-xs"> 
            <a href="#" class="probootstrap-burger-menu"><i>Menu</i></a>
            <h5>Connect</h5>
            <ul class="social-buttons">
              <li><a href="#"><i class="icon-twitter"></i></a></li>
              <li><a href="https://www.facebook.com/Premium-Sports-Nutrition-118799916173348/" target="_blank"><i class="icon-facebook2"></i></a></li>
              <li><a href="#"><i class="icon-instagram2"></i></a></li>
            </ul>
          </div>
        </nav>
    </div>
  </header>
  <!-- END: header -->

  <?php require_once(ROOT . 'include' . DS . 'notifications.inc.php'); ?>