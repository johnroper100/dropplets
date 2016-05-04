<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title><?php echo($blog_title); ?> | <?php echo($page_title); ?></title>
        <!-- Theme for benlk.com -->
        <?php echo($page_meta); ?>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- Theme Styles -->
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>style.css">
		<!--<link rel="stylesheet" href="<?php echo($template_dir_url); ?>custom/style.css">-->
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>subdiv.css">
        <link href='https://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <?php if($post_status == "revealjs") { ?>
        <link rel="stylesheet" href="<?php echo($template_dir_url); ?>revealjs/reveal.min-bink.css">
        <?php } ?>
        
	<link rel="shortcut icon" href="<?php echo($template_dir_url); ?>custom/favicon.png">
	<script src="jquery-1.10.2.min.js"></script>        
        <?php get_header(); ?>
    </head>

<?php if($is_home) { ?>
    <body class="home">
			<?php include("intro.php");
 } elseif ($category) { ?>
    <body class="category">
	    <header>
		<div class="row">
		    <div class="one-quarter meta">
		    </div>
		    <div class="three-quarters post">
			<h1>Category: <?php echo(ucwords($category)); ?></h1>
			<p>
			    <a href="<?php echo(BLOG_URL); ?>" alt="Back to <?php echo($blog_title); ?> ">&larr;</a> 
			</p>
		    </div>
		</div>
	    </header>
<?php } ?>
        <body>
        <?php echo($content); ?>
       
        <?php get_footer(); ?>
	<?php echo($dropplets_version); ?>
    </body>
</html>
