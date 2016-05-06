<article class="single wrapper">
    <div class="chevron">
	

        <div class="post">
            <div class="thumbnail">
                <img src="<?php echo($post_image); ?>" alt="<?php echo($post_title); ?>" />
            </div>

            <?php echo($post); ?>

        </div><!-- /.post -->

        <div class="btn-group">
          <a class="btn" href="https://twitter.com/intent/tweet?screen_name=<?php echo($post_author_twitter); ?>&text=Re:%20<?php echo($post_link); ?>%20" data-dnt="true"><i class="fa fa-comment" title="Comment on Twitter"></i></a>
          <a class="btn" href="https://twitter.com/intent/tweet?text=&quot;<?php echo($post_title); ?>&quot;%20<?php echo($post_link); ?>%20via%20<?php echo($post_author_twitter); ?>" data-dnt="true"><i class="fa fa-share" title="Share on Twitter"></i></a>
          <a class="btn" href="<?php echo($blog_url); ?>"><i class="fa fa-home" title="Return Home"></i></a>
        </div>

        <div class="meta">
            <ul>
                <li><i class="fa fa-pencil"></i> <a href="<?php echo($post_author['url']); ?>"><?php echo($post_author['name']); ?></a></li>
                <li><a href="<?php echo($post_link);?>"><i class="fa fa-calendar"></i> <?php echo($published_date);?></a></li>
                <li><?php foreach($post_categories_links as $key => $post_category_link): ?><a href="<?php echo($post_category_link); ?>"><?php
echo($post_categories[$key]); ?></a>, <?php endforeach; ?></li>
            </ul>
        </div>

        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>

    </div><!-- /.chevron -->

</article>
