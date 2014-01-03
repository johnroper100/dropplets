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

            <div class="fa-actions">
                    <a href="https://twitter.com/intent/tweet?screen_name=<?php echo($post_author_twitter); ?>&text=Re:%20<?php echo($post_link); ?>%20" data-dnt="true" title="<?php _e('Comment on'); ?> Twitter"><i class="fa-df fa-eb faf-twitterc fa-comment-o"></i></a>
                    <a href="https://twitter.com/intent/tweet?text=&quot;<?php echo($post_title); ?>&quot;%20<?php echo($post_link); ?>%20via%20&#64;<?php echo($post_author_twitter); ?>" data-dnt="true" title="<?php _e('Share on'); ?> Twitter"><i class="fa-df fa-eb faf-twitter fa-twitter"></i></a>
                    <a href="javascript:OpenWind('590', '320','https://www.facebook.com/sharer/sharer.php?u=<?php echo(urlencode($post_link)); ?>&t=<?php echo(urlencode($post_title)); ?>')" title="<?php _e('Share on'); ?> Facebook"><i class="fa-df fa-eb faf-facebook fa-facebook"></i></a>
                    <a href="javascript:OpenWind('518', '365','https://plus.google.com/share?url=<?php echo(urlencode($post_link)); ?>')" title="<?php _e('Share on'); ?> Google+"><i class="fa-df fa-eb faf-googlep fa-google-plus"></i></a>
                    <a href="javascript:OpenWind('500', '450','http://www.tumblr.com/share')" title="<?php _e('Share on'); ?> Tumblr"><i class="fa-df fa-eb faf-tumblr fa-tumblr"></i></a>

                    <a href="<?php echo($blog_url); ?>" title="<?php _e('More Articles'); ?>"><i class="fa-df fa-eb fa-map-marker"></i></a>
            </div>
        </div>
    </div>    
</article>
