<?php
	require_once('init.inc.php');

	// Get the articles
	$articles = $db->query("
		SELECT *, news_category_name
		FROM news_articles
		LEFT JOIN news_categories ON (news_article_category = news_category_id)
		ORDER BY news_article_id DESC
	");
?>
<!DOCTYPE html>
<html>
	<head>
		<?php require_once(ROOT . 'includes' . DS . 'meta.inc.php'); ?>
		<title>Northampton News - Administration - Articles</title>
	</head>
	<body>
        <?php require_once(ROOT . 'includes' . DS . 'header.inc.php'); ?>

        <?php require_once(ROOT . 'includes' . DS . 'nav.inc.php'); ?>
		
		<main>
            <?php require_once('includes' . DS . 'sidebar.inc.php'); ?>

			<article>
				<h3>Articles</h3>
				<p><br><a href="article.php?action=new" class="btn">New article</a></p>
				<table>
					<thead>
						<tr>
							<th>ID</th>
							<th>Title</th>
							<th>Category</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<?php
						if(!empty($articles)):
					?>
					<tbody>
					<?php
							foreach($articles as $article):
					?>
						<tr>
							<td><?=htmlentities($article['news_article_id'])?></td>
							<td><?=htmlentities($article['news_article_title'])?></td>
							<td><?=htmlentities($article['news_category_name'])?></td>
							<td><?=($article['news_article_status'] ? 'Active' : 'Inactive')?></td>
							<td><a href="article.php?action=edit&id=<?=htmlentities($article['news_article_id'])?>">Edit</a> | <a href="article.php?action=delete&id=<?=htmlentities($article['news_article_id'])?>">Delete</a></td>
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
							<td colspan="5" class="text-center">There are no articles to display.</td>
						</tr>
					</tbody>
					<?php
						endif;
					?>
				</table>
			</article>
		</main>

        <?php require_once(ROOT . 'includes' . DS . 'footer.inc.php'); ?>

	</body>
</html>
