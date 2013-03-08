<article>
    <div class="row">
        <div class="one-quarter meta">
            <div class="thumbnail">
                <img src="https://api.twitter.com/1/users/profile_image?screen_name=<?php echo $twitter ?>&size=bigger" alt="profile" />
            </div>
            
            <ul>
                <li>I'm <?php echo $author ?></li>
                <li><a href="mailto:<?php echo $email ?>?subject=Hello%20Jason"><?php echo $email ?></a></li>
                <li><a href="http://twitter.com/<?php echo $twitter ?>">&#64;<?php echo $twitter ?></a></li>
                <li></li>
            </ul>
        </div>
        
        <div class="three-quarters post">
            <h2><?php echo $intro_title ?></h2>
            
            <p><?php echo $intro_text ?></p>
            
            <ul class="actions">
                <li><a class="button" href="https://twitter.com/intent/tweet?screen_name=<?php echo $twitter ?>" data-dnt="true">Ping Me On Twitter</a></li>
            </ul>
            
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    </div>
</article>