<article class="full">
    <div class="look" style="background-image: url('<?php echo($post_image); ?>');">
    </div>

    <div class="read">
        <div class="post">
            <h2><?php echo($post_title); ?></h2>

            <div class="meta">
                Published on <?php echo($published_date); ?> by <a href="<?php echo($post_author['url']); ?>"><?php echo($post_author['name']); ?></a> in <?php foreach($post_categories_links as $key => $post_category_link): ?><a href="<?php echo($post_category_link); ?>"><?php
echo($post_categories[$key]); ?></a> <?php endforeach; ?>

                <div class="share">
                    <a class="twitter" href="https://twitter.com/intent/tweet?text=&quot;<?php echo($post_title); ?>&quot;%20<?php echo($post_link); ?>%20via%20&#64;<?php echo($post_author_twitter); ?>" data-dnt="true" title="Share on Twitter">t</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                    <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2F<?php echo($post_link); ?>" target="_blank" title="Share on Facebook">f</a>
                    <a class="google" title="Share on Google" href="https://plus.google.com/share?url=<?php echo($post_link); ?>" onclick="javascript:window.open(this.href,'', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;">g</a>
                </div>
            </div>

            <?php echo($post_content); ?>
        </div>
    </div>
</article>