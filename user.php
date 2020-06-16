<?php
	require_once('init.inc.php');

	// Show specific user
	$user = new User();

	// Initialise the comments array
	$comments = [];
	
	if(isset($_GET['id']) && $user->setFromId($_GET['id']) && $user->getStatus() == 1){
		// Set the page title
		$title = $user->getName();

		// Get the comments
        $comments = $user->getComments();
        
        // Get the articles
        $articles = $user->getArticles();
	} else {
		// Add a notification to the session and redirect the user to the articles list
		$notification = new Notification("The user you requested was not found.");
		$notification->redirect("articles.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once(ROOT . 'includes' . DS . 'meta.inc.php'); ?>
		<title>Northampton News - <?=$title?></title>
	</head>
	<body>
        <?php require_once(ROOT . 'includes' . DS . 'header.inc.php'); ?>

        <?php require_once(ROOT . 'includes' . DS . 'nav.inc.php'); ?>
		
		<main>
            <div class="articles">
                <h3>Articles by <?=htmlentities($user->getName())?> (<?=count($articles)?>)</h3>
                <?php
                // Check if there are any articles to display
                if(!empty($articles)):
                    // Iterate through each article and display it
                    foreach($articles as $article):
                    ?>
                    <div class="article-container">
                        <a href="article.php?id=<?=htmlentities($article->getId())?>" class="article-title"><?=htmlentities($article->getTitle())?></a>
                        <span class="article-meta">Published in <a href="articles.php?category=<?=htmlentities($article->getCategory()->getId())?>"><?=htmlentities($article->getCategory()->getName())?></a> on <?=date('F jS, Y', strtotime($article->getCreatedOn()))?> by <?=htmlentities($article->getUser()->getName())?> | <a href="article.php?id=<?=htmlentities($article->getId())?>#comments"><?=htmlentities($article->getCommentsNum())?> Comments</a></span>
                        <div class="article-description">
                            <p><?=nl2br(substr($article->getText(), 0, 500))?>...</p>
                        </div>
                    </div>
                    <?php
                    endforeach;
                else:
                    // No articles found
                ?>
                <p>There are no articles by this user.</p>
                <?php
                endif;
                ?>
            </div>
			<div class="comments-container">
				<h3 id="comments">Comments by <?=htmlentities($user->getName())?> (<?=count($comments)?>)</h3>
				<?php
                if(!empty($comments)):
                    echo $user->displayComments($comments);
                else:
                ?>
                <p>There are no comments by this user.</p>
                <?php
                endif;
				?>
			</div>
			
		</main>

        <?php require_once(ROOT . 'includes' . DS . 'footer.inc.php'); ?>

	</body>
</html>
