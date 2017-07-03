<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <title><?php echo($blog_title); ?> | <?php echo($page_title); ?></title>

        <?php echo($page_meta); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>css/screen.css">
	<link rel="stylesheet" href="<?php echo($template_dir_url); ?>css/font-awesome.min.css">

        <link rel="shortcut icon" href="<?php echo($blog_url); ?>/dropplets/style/images/favicon.png">

        <script src="<?php echo($template_dir_url); ?>js/min/authormarks.min.js" type="text/javascript"></script>

        <!-- <script src="//use.edgefonts.net/varela;source-sans-pro:n9;source-code-pro.js"></script> -->
        <script src="//use.edgefonts.net/varela;podkova:n7;source-code-pro.js"></script>

        <?php get_header(); ?>
    </head>

    <body>
	<a href="<?php echo($blog_url); ?>"><h1 class="title"><?php echo($blog_title); ?></h1></a>
        <?php if($is_home) { ?>
        <article class="wrapper">
            <div class="row chevron">

                <div class="post intro">
                    <div class="thumbnail">
                        <img src="<?php echo get_gravatar_profile_img($blog_email); ?>" alt="profile" />
                    </div>
                    <h1><?php echo($intro_title); ?></h1>

                    <p><?php echo($intro_text); ?></p>

                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                </div>

                <div class="meta">
                    <ul>
                        <li><i class="fa fa-user"></i> <?php echo($blog_title); ?></li>
                        <li><a href="mailto:<?php echo($blog_email); ?>?subject=Hello"><i class="fa fa-envelope"></i> <?php echo($blog_email); ?></a></li>
                        <li><a href="http://twitter.com/<?php echo($blog_twitter); ?>"><i class="fa fa-twitter"></i> &#64;<?php echo($blog_twitter); ?></a></li>
                    </ul>
                </div>
            </div>
        </article>
        <?php } ?>

        <?php echo($content); ?>

        <?php get_footer(); ?>
	<?php echo($dropplets_version); ?>
    </body>
</html>
