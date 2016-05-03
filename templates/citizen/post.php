<article class="single <?php echo($post_status); ?>">

	<header class="post-header">
		<h1 id="post-title"><a href="<?php echo($post_link); ?>" title="<?php echo($post_title); ?>"><?php echo($post_title); ?></a></h1>
		<div class="post-meta">
			<p>Published: <span><?php echo utf8_encode($published_date); ?></span></p>
			<p>Category: <span><a href="<?php echo($post_category_link); ?>"><?php echo($post_category); ?></a></span></p>
		</div>
	</header>
	
	<div class="post-text">
		<?php echo($post_content); ?>
		<br />
		<hr class="profile" />
		<br />
		<h3 class="profile"><span>Written by</span> <a href="http://twitter.com/<?php echo($post_author_twitter); ?>"><?php echo($post_author_twitter); ?></a></h3>
		<p><?php echo($intro_text); ?></p>
		<p><a class="button" href="https://twitter.com/intent/tweet?screen_name=<?php echo($post_author_twitter); ?>&text=Re:%20<?php echo($post_link); ?>%20" data-dnt="true">Comment on Twitter</a> // <a class="button" href="https://twitter.com/intent/tweet?text=&quot;<?php echo($post_title); ?>&quot;%20<?php echo($post_link); ?>%20via%20&#64;<?php echo($post_author_twitter); ?>" data-dnt="true">Share on Twitter</a> // <a class="button" href="mailto:<?php echo($blog_email); ?>">Reply via Email</a></p>
	</div>
	<div class="break"></div>
</article>
