
<?php
    // pulls file contents back together, minus header
    $raw_contents = join('', array_slice($fcontents, 6, $fcontents.length -1));
    // splits file at first set of eight dashes
    $parts = explode("--------", $raw_contents, 2);
    $revealjs = $parts[0]; // everything before the first dashes
    $markdown = $parts[1]; // everything after
?>
<div class="reveal-viewport">
    <div class="reveal">
        <div class="slides">
            <?php echo $revealjs ; ?>
        </div>
    </div>
</div>
<header class="single <?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">
        </div>
        <div class="three-quarters post">
            <h1><?php echo($post_title); ?></h1>
            <p>
                <?php echo($post_author); ?><br>
                <?php echo($published_date); ?><br>
                <a href="<?php echo(BLOG_URL); ?>" alt="Back to <?php echo($blog_title); ?> ">&larr;</a>
            </p>
        </div>
    </div>
</header>
<article class="single <?php echo($post_status); ?>">
    <div class="row">
        <div class="one-quarter meta">
        </div>
        <div class="three-quarters post">
            <?php echo(Markdown($markdown)); ?>
            <ul class="actions">
            <li><a class="button" href="https://twitter.com/intent/tweet?screen_name=<?php echo($post_author_twitter); ?>&text=Re:%20<?php echo($post_link); ?>%20" data-dnt="true">Comment on Twitter</a></li>
            <li><a class="button" href="https://twitter.com/intent/tweet?text=&quot;<?php echo($post_title); ?>&quot;%20<?php echo($post_link); ?>%20via%20&#64;<?php echo($post_author_twitter); ?>" data-dnt="true">Share on Twitter</a></li>
            <li><a class="button" href="<?php echo($blog_url); ?>">More Articles</a></li>
        </ul>
        </div>
    </div>
</article>

<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<script src="<?php echo $template_dir_url ;?>revealjs/reveal.min.js"></script>
<script>
    /* see https://github.com/hakimel/reveal.js/blob/master/README.md#configuration
     * CSS file is chosen in index.php
    */
    Reveal.initialize({
        controls: true,
        progress: true,
        history: true,
        center: true,
        overview: true,
        touch: true,
        embedded: true,
        mouseWheel: false,

        width: 720,
        height: 480,
        //margin: 0.1,
        //minScale: 0.2,
        //maxScale: 1.0,
    });
</script>