<article class="single <?php echo($post_status); ?>">
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
            <h2><?php echo($post_title); ?></h2>
            <?php echo($post_content); ?>

            <ul class="actions">
                <li><a class="button" href="https://twitter.com/intent/tweet?screen_name=<?php echo($post_author_twitter); ?>&text=Re:%20<?php echo($post_link); ?>%20" data-dnt="true"><?php _e('Comment on'); ?> Twitter</a></li>
                <li>
					<a class="button btnshare" href="https://twitter.com/intent/tweet?text=&quot;<?php echo($post_title); ?>&quot;%20<?php echo($post_link); ?>%20via%20&#64;<?php echo($post_author_twitter); ?>" data-dnt="true"><?php _e('Share on'); ?> Twitter</a>
				    <a class="button btnshare" href="javascript:OpenWind('590', '320','https://www.facebook.com/sharer/sharer.php?u=<?php echo(urlencode($post_link)); ?>&t=<?php echo(urlencode($post_title)); ?>')"><?php _e('Share on'); ?> Facebook</a>
					<a class="button" href="javascript:OpenWind('518', '365','https://plus.google.com/share?url=<?php echo(urlencode($post_link)); ?>')"><?php _e('Share on'); ?> Google+</a>
				</li>
                <li><a class="button" href="<?php echo($blog_url); ?>"><?php _e('More Articles'); ?></a></li>
            </ul>

            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    </div>
</article>
