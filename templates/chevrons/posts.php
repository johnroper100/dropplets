<article class="<?php echo($post_status); ?> wrapper">
    <div class="chevron">

        <div class="post">
            <div class="thumbnail">
                <img src="<?php echo($post_image); ?>" alt="<?php echo($post_title);?>" />
            </div>

            <h1><a href="<?php echo($post_link); ?>"><?php echo($post_title);?></a></h1>

            <?php echo $post_intro ?>

            <div class="button-wrap">
                <a class="btn" href="<?php echo($post_link); ?>" title="Continue reading..."><i class="icon-plus-sign"></i></a>
                <?php if ($category) { ?>
                <a class="btn" href="<?php echo($blog_url); ?>"><i class="icon-home" title="Return Home"></i></a>
                <?php } ?>
            </div>
        </div>

        <div class="meta">
            <ul>
                <li><i class="icon-pencil"></i> <a href="<?php echo($post_author['url']); ?>"><?php echo($post_author['name']); ?></a></li>
                <li><a href="<?php echo($post_link);?>"><i class="icon-calendar"></i> <?php echo($published_date);?></a></li>
                <li><?php foreach($post_categories_links as $key => $post_category_link): ?><a href="<?php echo($post_category_link); ?>"><?php
echo($post_categories[$key]); ?></a> <?php endforeach; ?></li>
            </ul>
        </div>

    </div>
</article>
