<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title><?php echo($blog_title); ?> | <?php echo($page_title); ?></title>
        
        <?php echo($page_meta); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>style.css">
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>subdiv.css">
        <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
        
        <?php get_header(); ?>
    </head>

    <body>
        <?php $path = explode('/',$_SERVER["REQUEST_URI"]); //the current path
            $puddle_image = '/templates/puddle/puddle.jpg'; //the default
            $puddle_main_image_exists = file_exists('./posts/puddle.jpg'); //with the dot, because it has to be an absolute path
            $puddle_post_image_exists = file_exists($image); //Post image already defaults to profile, so not that
            $puddle_main_image = '/posts/puddle.jpg'; //without the dot, because the CSS won't work with it
            if($puddle_main_image_exists) {
                $puddle_image = $puddle_main_image; //if we have a post image, override it with that
            }
            $puddle_is_home = (count(array_filter($path)) == 0); //if there's nothing in the exploded path
        if($puddle_is_home) { ?>
            <div id="background" style="background: url(<?php echo($puddle_image) ?>) no-repeat center center fixed;"></div>
            <div id="hero">
                <div id="hero-content">
                    <h1><?php echo($intro_title); ?></h1>
                </div>
            </div>
        <?php } else if($path[1] == "category") { //if the first directory is category ?>
            <div id="background" style="background: url(<?php echo($puddle_image) ?>) no-repeat center center fixed;"></div>
            <div id="hero">
                <div id="hero-content">
                    <h1><?php echo($post_category); ?></h1>
                </div>
            </div>
        <?php } else if($puddle_post_image_exists) { //if it's an image page ?>
            <div id="background" style="background: url(<?php echo($post_image)?>) no-repeat center center fixed;"></div>
            <div id="hero">
                <div id="hero-content">
                    <h1><?php echo($post_title); ?></h1>
                </div>
            </div>
        <?php } else { //if it's a post with no image ?>
            <div id="background" style="background: url(<?php echo($puddle_image) ?>) no-repeat center center fixed;"></div>
            <div id="hero">
                <div id="hero-content">
                    <h1><?php echo($post_title); ?></h1>
                </div>
            </div>
        <?php } ?>
        <div id="main">
            <?php if($is_home) { ?>
                <article id="header">
                    <div class="row">
                        <div id="info">
                            <!--<li><span><?php echo($blog_title); ?></span></li>-->
                            <span><a href="mailto:<?php echo($blog_email); ?>?subject=Hello"><?php echo($blog_email); ?></a></span>
                            <div class="thumbnail">
                                <img src="<?php echo get_twitter_profile_img($blog_twitter); ?>" alt="profile" />
                            </div>
                            <span><a href="http://twitter.com/<?php echo($blog_twitter); ?>">&#64;<?php echo($blog_twitter); ?></a></span>
                        </div>

                        <p class="three-quarters"><?php echo($intro_text); ?></p>
                    </div>
                </article>
            <?php } ?>
            <?php echo($content); ?>
        
            <?php get_footer(); ?>
	    <?php echo($dropplets_version); ?>
        </main>
    </body>
</html>
