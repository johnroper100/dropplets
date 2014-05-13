<?php
    $email = $post_author_email;
    $default = "http://dropplets.com/favicon.png";
    $size = 40;
    $grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".urlencode($default)."&s=".$size;
?>

<article class="<?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="<?php echo $grav_url; ?>" alt="<?php echo($post_title); ?>" />
            </div>

            <ul>
                <li>Written by <?php echo($post_author); ?></li>
                <li><?php echo($published_date); ?></li>
                <li>About <a href="<?php echo($post_category_link); ?>"><?php echo($post_category); ?></a></li>
                <li></li>
            </ul>
        </div>

        <div class="three-quarters post">
            <h2><a href="<?php echo($post_link); ?>"><?php echo($post_title); ?></a></h2>

            <?php echo($post_intro); ?>

            <ul class="actions">
                <li><a class="button" href="<?php echo($post_link); ?>">Continue Reading</a></li>
                <?php if ($category) { ?>
                <li><a class="button" href="<?php echo($blog_url); ?>">More Articles</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</article>
