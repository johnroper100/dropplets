<!DOCTYPE html>
<html lang="<?php echo str_replace("_","-",strtolower(language)); ?>">
    <head>
        <meta charset="<?php echo strtolower(encoding); ?>">       
        <title><?php echo($page_title); ?></title>    
        <?php
						if (isset($page_meta)) { // warning correction
							echo($page_meta);
						}
		?> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>style.css">
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>subdiv.css">
		<?php get_header(); ?>
		<!-- personalization -->
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>personal.css" >
    </head>

    <body>
	<?php echo($menu_site); ?>
	<?php if(IS_HOME) { ?>
        <article>
            <div class="row">
                <div class="one-quarter meta">
                    <div class="thumbnail">
                        <img src="<?php echo get_profile_img(); ?>" alt="profile" />
                    </div>
        
                    <ul>
                        <li><?php echo($blog_title); ?></li>
                        <li><a href="mailto:<?php echo($blog_email); ?>?subject=Hello"><?php echo($blog_email); ?></a></li>
                        <li><a href="http://twitter.com/<?php echo($blog_twitter); ?>">&#64;<?php echo($blog_twitter); ?></a></li>
                        <li></li>
                    </ul>
                </div>
        
                <div class="three-quarters post">
                    <h2><?php echo($intro_title); ?></h2>
        
                    <p><?php echo($intro_text); ?></p>
        
                </div>
            </div>
        </article>
        <?php } ?>
        
        <?php echo($content); ?>
        
        <?php get_footer(); ?>
    </body>
</html>
