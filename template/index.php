<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title><?php echo $site_title ?></title>
        
        <meta name="description" content="<?php echo $meta_description ?>">
        <meta name="author" content="<?php echo $author ?>">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo $stylesheet_dir ?>style.css">
        <link rel="stylesheet" href="<?php echo $site_url ?>/template/subdiv.css">
        
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
        
        <link rel="shortcut icon" href="<?php echo $site_url ?>/template/images/favicon.png">
    </head>
    
    <body>
        <?php echo $content ?>
    </body>
</html>
