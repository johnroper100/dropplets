<article class="single">
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="<?php echo $site->url ?>/dropplets/styles/images/404.jpg" alt="four-oh-four" />
            </div>
        </div>
        
        <div class="three-quarters article">
            <h1><?php echo $error->error_title ?></h1>
            <p><?php echo $error->error_text ?></p>
                                
            <ul class="actions">
                <li><a class="button" href="<?php echo $site->url ?>">More Articles</a></li>
            </ul>
        </div>
    </div>
</article>