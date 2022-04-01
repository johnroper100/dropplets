<?php
require "header.php";
require_once "./SleekDB/src/Store.php";

use SleekDB\Store;

$postsLoaded = 0;
?>
<!-- Add grid size capability in the future, right now its 3xNumPosts -->
<?php
foreach ($allPosts as $post) {
	// Create rows every 3 posts so that we create the 3xNumPosts Grid Layout
	if ($postsLoaded == 3 || $postsLoaded == 0) {?><div class="row align-items-start row-cols-lg-3 g-5 my-1"><?php }?>
	<div class="col">
		<div class="card">
			<?php
			if (is_numeric($post['image']) == TRUE) {
				$databaseDirectory = "./siteDatabase";
				$imageStore = new Store("images", $databaseDirectory);
				$imageRecord = $imageStore->findById($post['image']);
				echo '<img class="card-img-top" src="' . $imageRecord['url'] . '">';
			} elseif ($post['password'] != '') {
				echo '<img class="card-img-top" src="' . $siteConfig["domain"] . '/uploads/private.png">';
			} else {
				echo '<img class="card-img-top" src="' . $siteConfig["OGImage"] . '">';
			}?>
			<div class="card-body">
				<a href="<?php echo $router->generate('post', ['id' => $post['_id']]); ?>" class="text-decoration-none text-dark">
					<h3 class="card-title"><?php echo $post['title']; ?></h3>
				</a>
				<?php
				if ($post['password'] != '') { ?>
					<!-- Need to add to i18n -->
					<p class="card-text text-muted">This post is protected<br><?php echo date('F j, Y', $post['date']); ?></p>
				<?php
				} else { ?>
					<p class="card-text text-muted">Posted by <?php echo $post['author']; ?><br><?php echo date('F j, Y', $post['date']); ?></p>
				<?php } ?>
			</div>
		</div>
	</div>

	<?php
	++$postsLoaded;
	if ($postsLoaded == 3) {
		?></div><?php
		$postsLoaded = 0;
	}
}?></div>
<div class="row my-5">
	<div class="col-md-3"></div>
    <div class="col-md-6">
		<nav>
			<ul class="pagination justify-content-center">
				<li class="page-item"><a class="page-link" href="/">Home</a></li>
				<?php if ($numPages > 2){
						$page = 1;
						while($page <= $numPages-2) {
							echo '<li class="page-item"><a class="page-link" href="/'. $page+1 .'">'. $page+1 .'</a></li>';
							$page++;
						}
				}?>
				<li class="page-item"><a class="page-link" href="/<?php echo $numPages; ?>">Last</a></li>
			</ul>
		</nav>
	</div>
    <div class="col-md-3"></div>
</div>
<?php
require "footer.php";
?>