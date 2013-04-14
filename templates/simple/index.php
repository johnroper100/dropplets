<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title><?php echo $site_title ?></title>
        
        <meta name="description" content="<?php echo $meta_description ?>">
        <meta name="author" content="<?php echo $author ?>">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo $template_dir_url ?>style.css">
        <link rel="stylesheet" href="<?php echo $template_dir_url ?>subdiv.css">
        
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
        
        <link rel="shortcut icon" href="./dropplets/style/images/favicon.png">
    </head>
    
    <body>
        <?php echo $content ?>
        
        <?php echo stripslashes($tracking_code) ?>
    </body>
</html>
