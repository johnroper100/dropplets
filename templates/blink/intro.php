<header class="intro">
    <div class="row">
        <div class="one-quarter meta">

            
        </div>

        <div class="three-quarters">
            <h1><?php echo $intro_title ?></h1>

            <p><?php echo $intro_text ?></p>
			
            <ul>
                <li><a href="mailto:<?php echo $blog_email ?>?subject=Hello"><?php echo $blog_email ?></a></li>
                <li><a href="http://twitter.com/<?php echo $blog_twitter ?>">&#64;<?php echo $blog_twitter ?></a></li>
				<?php 
				include "custom/intro-buttons.php" 
				// These are if you want to put some buttons in your header. 
				?>
            </ul>
			
			
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    </div>
</header>
