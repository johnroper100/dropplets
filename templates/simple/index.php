<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title><?php echo $site_title ?></title>
        
        <meta name="description" content="<?php echo $meta_description ?>">
        <meta name="author" content="<?php echo $author ?>">
        <link rel="author" href="<?php echo $author_google ?>"/>
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo $template_dir_url ?>style.css">
        <link rel="stylesheet" href="<?php echo $template_dir_url ?>subdiv.css">
        
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
        
        <link rel="shortcut icon" href="./dropplets/style/images/favicon.png">
        <meta property='og:title' content='<?php echo $site_title ?>'>
        <meta property='og:description' content='<?php echo $meta_description ?>'>
        <meta property='og:url' content='<?php echo $blog_url ?>'>
        <meta property='og:site_name' content='<?php echo $site_title ?>'>
        <meta property='og:type' content='article'/>
        <meta property='og:image' content='https://api.twitter.com/1/users/profile_image?screen_name=<?php echo $blog_twitter ?>&size=bigger'/>
        <meta name="twitter:creator" content="@<?php echo $blog_twitter ?>">
        <meta name="twitter:card" content="summary">
        <meta name="twitter:site" content="@<?php echo $blog_twitter ?>">
	    <meta name="twitter:url" content="<?php echo $blog_url ?>">
        <meta name="twitter:title" content="<?php echo $site_title ?>">
        <meta name="twitter:description" content="<?php echo $meta_description ?>">
        <meta name="twitter:image" content="https://api.twitter.com/1/users/profile_image?screen_name=<?php echo $blog_twitter ?>&size=bigger">
        <meta name="twitter:domain" content="<?php echo $author_url ?>">
    </head>
    
    <body>
        <?php echo $content ?>
        
        <?php echo stripslashes($tracking_code) ?>
    </body>
</html>
