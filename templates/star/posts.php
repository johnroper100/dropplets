<article class="<?php echo($post_status); ?>">
	
	<img id="postImage" src="<?php echo($post_image); ?>">

	<header class="post-header">
		<h1 id="post-title"><a href="<?php echo($post_link); ?>" title="<?php echo($post_title); ?>"><?php echo($post_title); ?></a></h1>
		<div class="post-meta">
			<p>Written by: <a href="<?php echo($post_author['url']); ?>"><?php echo($post_author['name']); ?></a></p>
			<p>Published: <span><?php echo utf8_encode($published_date); ?></span></p>
			<p>Category: <span><?php foreach($post_categories_links as $key => $post_category_link): ?><a href="<?php echo($post_category_link); ?>"><?php
echo($post_categories[$key]); ?></a>, <?php endforeach; ?></span></p>
		</div>
	</header>
	
	<div class="post-text">
		<?php echo($post_intro); ?>
		<p><a class="more" href="<?php echo($post_link); ?>">Read On&hellip;</a></p>
	</div>
	<div class="break"></div>
</article>
