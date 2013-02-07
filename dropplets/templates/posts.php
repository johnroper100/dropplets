<article>
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="<?php echo $post_thumbnail; ?>" alt="<?php echo $post_title; ?>" />
            </div>
            
            <ul>
                <li>Written by <?php echo $site->author->name ?></li>
                <li><?php echo $published_date; ?></li>
                <li>About <?php echo $post_category; ?></li>
            </ul>
        </div>
        
        <div class="three-quarters article">
            <h2><a href="<?php echo $post_link; ?>"><?php echo $post_title; ?></a></h2>
            <p><?php echo $post_intro; ?></p>
            <a class="button" href="<?php echo $post_link; ?>">Continue Reading</a>
        </div>
    </div>
</article>