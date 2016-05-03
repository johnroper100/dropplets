<article class="<?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="<?php echo($post_image); ?>" alt="<?php echo($post_title); ?>" />
            </div>

            <ul>
                <li>Written by <a href="<?php echo($post_author['url']); ?>"><?php echo($post_author['name']); ?></a></li>
                <li><?php echo($published_date); ?></li>
                <li>Categories: <?php foreach($post_categories_links as $key => $post_category_link): ?><a href="<?php echo($post_category_link); ?>"><?php
echo($post_categories[$key]); ?></a> <?php endforeach; ?></li>
                <li></li>
            </ul>
        </div>

        <div class="three-quarters post">
            <h2><a href="<?php echo($post_link); ?>"><?php echo($post_title); ?></a></h2>

            <?php echo($post_intro); ?>

            <ul class="actions">
                <li><a class="button" href="<?php echo($post_link); ?>">Continue Reading</a></li>
                <?php if ($category) { ?>
                <li><a class="button" href="<?php echo($blog_url); ?>">Back</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</article>
