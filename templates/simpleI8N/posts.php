<article class="<?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="<?php echo($post_image); ?>" alt="<?php echo($post_title); ?>" />
            </div>

            <ul>
                <li><?php _e('Written by'); ?> <?php echo($post_author); ?></li>
                <li><?php echo($published_date); ?></li>
                <li><?php _e('About'); ?> <a href="<?php echo($post_category_link); ?>"><?php echo($post_category); ?></a></li>
                <li></li>
            </ul>
        </div>

        <div class="three-quarters post">
            <h2><a href="<?php echo($post_link); ?>"><?php echo($post_title); ?></a></h2>

            <?php echo($post_intro); ?>

			
			<div class="fa-actions">
				<a href="<?php echo($post_link); ?>"><?php _e('Continue Reading'); ?><i class="fa-df fa-plus-square-o"></i></a>
				<?php if ($category) { ?>  
					<a href="<?php echo($blog_url); ?>"><?php _e('More Articles'); ?><i class="fa-df fa-map-marker"></i></a>
				<?php } ?>
			</div>	
        </div>
    </div>
</article>
