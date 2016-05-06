<article class="<?php echo($post_status); ?> wrapper">
    <div class="chevron">

        <div class="post">
            <div class="thumbnail">
                <img src="<?php echo($post_image); ?>" alt="<?php echo($post_title);?>" />
            </div>

            <h1><a href="<?php echo($post_link); ?>"><?php echo($post_title);?></a></h1>

            <?php echo $post_intro ?>

            <div class="button-wrap">
                <a class="btn" href="<?php echo($post_link); ?>" title="Continue reading..."><i class="fa fa-share"></i>Continue</a>
                <?php if ($category) { ?>
                <a class="btn" href="<?php echo($blog_url); ?>"><i class="fa fa-home" title="Return Home"></i></a>
                <?php } ?>
            </div>
        </div>

        <div class="meta">
            <ul>
                <li><i class="fa fa-pencil"></i> <a href="<?php echo($post_author['url']); ?>"><?php echo($post_author['name']); ?></a></li>
                <li><i class="fa fa-calendar"></i> <a href="<?php echo($post_link);?>"><?php echo($published_date);?></a></li>
                <li><i class="fa fa-tags"></i> <?php foreach($post_categories_links as $key => $post_category_link): ?><a href="<?php echo($post_category_link); ?>"><?php
echo($post_categories[$key]); ?></a>, <?php endforeach; ?></li>
            </ul>
        </div>

    </div>
</article>
