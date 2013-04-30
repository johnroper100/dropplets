<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <title><?php echo $page_title ?></title>

        <?php echo $page_meta ?>

        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="alternate" type="application/rss+xml" title="Subscribe using RSS" href="/rss" />
        <link rel="alternate" type="application/atom+xml" title="Subscribe using Atom" href="/atom" />

        <link rel="stylesheet" href="<?php echo $template_dir_url ?>style.css">

        <link href='http://fonts.googleapis.com/css?family=Merriweather' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Cabin+Condensed:400,500' rel='stylesheet' type='text/css'>

        <link rel="shortcut icon" href="<?php echo $blog_url ?>/dropplets/style/images/favicon.png">

        <?php echo stripslashes($header_inject) ?>
    </head>

    <body>
        <?php echo $content ?>

        <?php echo $powered_by ?>

        <?php echo stripslashes($footer_inject) ?>
    </body>
</html>
