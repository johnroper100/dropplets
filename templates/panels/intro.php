<article id="intro">
    <div class="inside">
        <h2><?php echo $blog_title ?></h2>
        <p><?php echo $intro_text ?></p>

        <ul class="actions">
            <li>
                <a class="big charcoal left button" href="mailto:<?php echo $blog_email ?>?subject=Hello"><?php echo $blog_email ?></a>
                <a class="big gray button right" href="http://twitter.com/<?php echo $blog_twitter ?>">&#64;<?php echo $blog_twitter ?></a>
            </li>
        </ul>

        <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    </div>
</article>