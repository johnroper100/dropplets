<article class="<?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">

            <ul>
                <li><?php echo($published_date); ?></li>
                <li><a href="<?php echo($post_category_link); ?>"><?php echo($post_category); ?></a></li>
            </ul>
        </div>

        <div class="three-quarters post">
            
            <?php echo($post_content); ?>
        </div>
    </div>
</article>