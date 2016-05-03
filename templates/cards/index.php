<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title><?php echo($blog_title); ?> | <?php echo($page_title); ?></title>
        
        <?php echo($page_meta); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>style.css">
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>style-mobile.css">
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
        
        <?php get_header(); ?>
    </head>

    <body>
        <header class="<?php if($is_home) { ?>home<?php } else { ?>single<?php } ?>">
            <div class="profile-mask">
            </div>
            
            <div class="profile">
            </div>
            
            <?php if($is_home) { ?>
            <div class="profile-image">
                <img src="<?php echo get_twitter_profile_img($blog_twitter); ?>" alt="<?php echo($blog_title); ?>" />
            </div>
            <?php } else { ?>
            <a class="profile-image" href="<?php echo($blog_url); ?>" title="<?php echo($blog_name); ?>">
                <img src="<?php echo get_twitter_profile_img($blog_twitter); ?>" alt="<?php echo($blog_title); ?>" />
            </a>
            <?php } ?>
            
            <?php if($is_home) { ?>
            <div class="intro">
                <h1><?php echo($intro_title); ?></h1>
                <p><?php echo($intro_text); ?></p>
                <a href="mailto:<?php echo($blog_email); ?>?subject=Hello"><?php echo($blog_email); ?></a>
            </div>
            <?php } ?>
        </header>
        
        <section class="<?php if($is_home) { ?>home<?php } else { ?>single<?php } ?>">
            <?php echo($content); ?>
        </section>
        
        <?php get_footer(); ?>
	<?php echo($dropplets_version); ?>
        
        <script type="text/javascript">
            <?php if($is_home) { ?>
            var headerResize = function() {
                var headerhomeheight = $('header').height()-60;
                var headermaskheight = $('header').height()-75;
                var headerimageheight = $('header').height()-50;
                $("header.home").css({ bottom: '0px' });
                
                setTimeout(function() { 
                    $("header.home").css({ bottom: '-' + headerhomeheight + 'px' });
                }, 2000);
                
                
            };
            
            $(document).ready(function () {
                headerResize();
            });
            
            $(window).resize(function() {
                headerResize();
            });
            <?php } ?>
        </script>
    </body>
</html>
