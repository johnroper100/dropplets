<article class="single <?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">
        </div>
        <div class="three-quarters post">
            <?php echo($post_content); ?>
            <hr/>
            <p>
                <?php echo($published_date); ?><br/>
                <a href="<?php echo $post_author['url'] ?>"><?php echo $post_author['name']; ?></a><br>
                <a href="<?php echo(BLOG_URL); ?>" alt="Back to <?php echo($blog_title); ?> ">&larr; back to actual content</a> 
            </p>
            <ul class="actions">
                <li><a class="button" href="https://twitter.com/intent/tweet?screen_name=<?php echo($post_author_twitter); ?>&text=Re:%20<?php echo($post_link); ?>%20" data-dnt="true">Comment on Twitter</a></li>
                <li><a class="button" href="https://twitter.com/intent/tweet?text=&quot;<?php echo($post_title); ?>&quot;%20<?php echo($post_link); ?>%20via%20&#64;<?php echo($post_author_twitter); ?>" data-dnt="true">Share on Twitter</a></li>
                <li><a class="button" href="<?php echo($blog_url); ?>">More Articles</a></li>
            </ul>
        </div>
    </div>
</article>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>