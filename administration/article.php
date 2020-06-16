<?php
    require_once('init.inc.php');
    require_once(ROOT . 'administration' . DS . 'lib' . DS . 'article_manager.class.php');

    // Set up an article manager
    $manager = new ArticleManager();
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once(ROOT . 'includes' . DS . 'meta.inc.php'); ?>
		<title>Northampton News - Administration - <?=$manager->getTitle()?></title>
	</head>
	<body>
        <?php require_once(ROOT . 'includes' . DS . 'header.inc.php'); ?>

        <?php require_once(ROOT . 'includes' . DS . 'nav.inc.php'); ?>
		
		<main>
            <?php require_once('includes' . DS . 'sidebar.inc.php'); ?>
			
            <article>
                <h3><?=$manager->getTitle()?></h3>

                <?=$manager->getForm()?>

            </article>
		</main>

        <?php require_once(ROOT . 'includes' . DS . 'footer.inc.php'); ?>

	</body>
</html>
