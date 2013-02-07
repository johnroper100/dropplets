<article>
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="https://api.twitter.com/1/users/profile_image?screen_name=<?php echo $site->author->twitter ?>&size=bigger" alt="profile" />
            </div>
            
            <ul>
                <li>I'm <?php echo $site->author->name ?></li>
                <li><a href="mailto:<?php echo $site->author->email ?>?subject=Hello%20Jason"><?php echo $site->author->email ?></a></li>
                <li><a href="http://twitter.com/<?php echo $site->author->twitter ?>">&#64;<?php echo $site->author->twitter ?></a></li>
            </ul>
        </div>
        
        <div class="three-quarters article">
            <h2><?php echo $intro->intro_title ?></h2>
            <p><?php echo $intro->intro_text ?></p>
            <a class="button" href="https://twitter.com/intent/tweet?screen_name=<?php echo $site->author->twitter ?>" data-dnt="true">Ping Me On Twitter</a>
            
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    </div>
</article>