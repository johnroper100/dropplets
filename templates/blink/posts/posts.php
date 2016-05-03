<article class="<?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">
            
        </div>
        <div class="three-quarters post">
            <h2><a href="<?php echo($post_link); ?>"><?php echo($post_title); ?></a></h2>
        </div>
    </div>
    <div class="row">
        <div class="one-quarter meta">
            <ul>
                <li><?php echo($post_author); ?></li>
                <li><?php echo($published_date); ?></li>
                <li><a href="<?php echo($post_category_link); ?>"><?php echo($post_category); ?></a></li>
            </ul>
        </div>

        <div class="three-quarters post">
            <?php echo($post_intro); ?>

            <ul class="actions">
                <li><a class="button" href="<?php echo($post_link); ?>">Continue Reading &rarr;</a></li>
            </ul>
        </div>
    </div>
</article>