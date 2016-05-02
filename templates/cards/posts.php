<article class="intro">
    <a class="post-link" href="<?php echo($post_link); ?>" title="<?php echo($post_title); ?>"></a>
    
    <div class="look" style="background-image: url('<?php echo($post_image); ?>');">
    </div>
    
    <div class="read">
        <div class="post">
            <h2><?php echo($post_title); ?></h2>
            
            <span class="meta"><?php echo($published_date); ?> by <?php echo($post_author); ?></span>
            
            <p><?php echo($post_intro); ?></p>
        </div>
    </div>
    
    <div class="shadow"></div>
</article>