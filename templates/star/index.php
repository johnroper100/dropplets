<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title><?php echo($blog_title); ?> | <?php echo($page_title); ?></title>
        
        <?php echo($page_meta); ?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>normalize.css">
	<link rel="stylesheet" href="<?php echo($template_dir_url); ?>style.css">

	<script src="<?php echo($template_dir_url); ?>pace.min.js"></script>
    	<link href="<?php echo($template_dir_url); ?>dataurl.css" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700,500&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
        
        <?php get_header(); ?>
    </head>

    <body>
	
		<div id="container">
			<header id="site">
				<div id="site-logo"><a href="<?php echo($blog_url); ?>" title="<?php echo($blog_title); ?>"><img src="<?php echo get_gravatar_profile_img($blog_email); ?>" /></a></div>
				<h1 id="site-header"><a href="<?php echo($blog_url); ?>" title="<?php echo($blog_title); ?>"><?php echo($blog_title); ?></a></h1>
			</header>
			
			<section id="content">
	
        <?php if($is_home) { ?>

        <?php } ?>
        
        <?php echo($content); ?>

        <?php echo(isset($pagination) ? $pagination : "") ?>
        
        <?php get_footer(); ?>
	<?php echo($dropplets_version); ?>
		
		</section>
		
		</div>
    </body>
</html>
