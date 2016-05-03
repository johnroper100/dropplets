<article class="<?php echo($post_status); ?>">

	<header class="post-header">
		<h1 id="post-title"><a href="<?php echo($post_link); ?>" title="<?php echo($post_title); ?>"><?php echo($post_title); ?></a></h1>
		<div class="post-meta">
			<p>Written by: <a href="http://twitter.com/<?php echo($post_author_twitter); ?>"><?php echo($post_author_twitter); ?></a></p>
			<p>Published: <span><?php echo utf8_encode($published_date); ?></span></p>
			<p>Category: <span><a href="<?php echo($post_category_link); ?>"><?php echo($post_category); ?></a></span></p>
		</div>
	</header>
	
	<div class="post-text">
		<?php echo($post_intro); ?>
		<p><a class="more" href="<?php echo($post_link); ?>">Read On&hellip;</a></p>
	</div>
	<div class="break"></div>
</article>
