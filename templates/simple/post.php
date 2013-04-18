<meta property='og:title' content='<?php echo $post_title ?>'>
<meta property='og:description' content='<?php echo $post_intro ?>'>
<meta property='og:url' content='<?php echo $post_link ?>'>
<meta property='og:image' content='<?php echo $post_feat_image ?>'>
<meta name="twitter:creator" content="@<?php echo $blog_twitter ?>">
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="@<?php echo $blog_twitter ?>">
<meta name="twitter:url" content="<?php echo $post_link ?>">
<meta name="twitter:title" content="<?php echo $post_title ?>">
<meta name="twitter:description" content="<?php echo $post_intro ?>">
<meta name="twitter:image" content="<?php echo $post_feat_image ?>">
<meta name="twitter:domain" content="<?php echo $author_url ?>">
    
<div style="margin-right:auto;margin-left:auto;max-width:100%;text-align:center;">
    <img src="<?php echo $post_feat_image ?>" alt="<?php echo $post_title ?>" style="max-width:100%;height:auto;"/>
</div>
<article class="single" itemscope itemtype="http://schema.org/BlogPosting">
    <meta itemprop="url" content="<?php echo $post_link ?>">
    <meta itemprop="headline" content="<?php echo $post_title ?>">
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="<?php echo $post_image ?>" alt="<?php echo $post_title ?>" itemprop="image"/>
            </div>
            
            <ul>
                <li>By <span itemprop="author"><?php echo $post_author ?></span></li>
                <li itemprop="datePublished"><?php echo $published_date ?></li>
                <li>About <span itemprop="articleSection"><?php echo $post_category ?></span></li>
                <li></li>
            </ul>
        </div>
        
        <div class="three-quarters post">
            <span itemprop="articleBody"><?php echo $post ?></span>
                                
            <ul class="actions">
                <li><a class="button" href="https://twitter.com/intent/tweet?screen_name=<?php echo $post_author_twitter ?>&text=Re:%20<?php echo $post_link ?>%20" data-dnt="true">Comment on Twitter</a></li>
                <li><a class="button" href="https://twitter.com/intent/tweet?text=&quot;<?php echo $post_title ?>&quot;%20<?php echo $post_link ?>%20via%20&#64;<?php echo $post_author_twitter ?>" data-dnt="true">Share on Twitter</a></li>
                <li><a class="button" href="<?php echo $blog_url ?>">More Articles</a></li>
            </ul>
            
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    </div>
</article>