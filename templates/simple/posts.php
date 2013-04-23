<article class="<?php echo $post_status ?>">
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="<?php echo $post_image ?>" alt="<?php echo $post_title ?>" />
            </div>

            <ul>
                <li>Written by <?php echo $post_author ?></li>
                <li><?php echo $published_date ?></li>
                <li>About <?php echo $post_category ?></li>
                <li></li>
            </ul>
        </div>

        <div class="three-quarters post">
            <h2><a href="<?php echo $post_link ?>"><?php echo $post_title ?></a></h2>

            <?php echo $post_intro ?>

            <ul class="actions">
                <li><a class="button" href="<?php echo $post_link ?>">Continue Reading</a></li>
            </ul>
        </div>
    </div>
</article>