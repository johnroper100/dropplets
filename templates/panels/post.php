<article class="single">
    <div class="inside">
        <?php echo $post ?>

        <ul class="actions">
            <li>
                <a class="big black button left" href="https://twitter.com/intent/tweet?screen_name=<?php echo $post_author_twitter ?>&text=Re:%20<?php echo $post_link ?>%20" data-dnt="true">Comment on Twitter</a>
                <a class="big charcoal button center" href="https://twitter.com/intent/tweet?text=&quot;<?php echo $post_title ?>&quot;%20<?php echo $post_link ?>%20via%20&#64;<?php echo $post_author_twitter ?>" data-dnt="true">Share on Twitter</a>
                <a class="big gray button right" href="<?php echo $blog_url ?>">More Articles</a>
            </li>
        </ul>

        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    </div>
</article>