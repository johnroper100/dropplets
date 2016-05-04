<article class="<?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">

            <ul>
                <li><?php echo($published_date); ?></li>
                <li><?php foreach($post_categories_links as $key => $post_category_link): ?><a href="<?php echo($post_category_link); ?>"><?php
echo($post_categories[$key]); ?></a> <?php endforeach; ?></li>
            </ul>
        </div>

        <div class="three-quarters post">
            
            <?php echo($post_content); ?>
        </div>
    </div>
</article>