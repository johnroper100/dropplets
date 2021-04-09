<!DOCTYPE html>
<html>

<head>
    <?php
    if ($siteConfig['headerInject'] != "") {
        echo base64_decode($siteConfig['headerInject']);
    }
    ?>

    <meta charset="utf-8">
    <title><?php if ($siteConfig['name'] != "") {
                echo $siteConfig['name'];
            } else {
                echo "Dropplets";
            } ?> | <?php echo $pageTitle; ?></title>

    <!-- Search Engine -->
    <?php if ($post['image'] != "") { ?>
    <meta name="image" content="<?php echo $post['image']; ?>">
    <?php } ?>
    <!-- Schema.org for Google -->
    <meta itemprop="name" content="<?php if ($siteConfig['name'] != "") {echo $siteConfig['name'];} else {echo "Dropplets";} ?> | <?php echo $pageTitle; ?>">
    <?php if ($post['image'] != "") { ?>
    <meta itemprop="image" content="<?php echo $post['image']; ?>">
    <?php } ?>
    <!-- Twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php if ($siteConfig['name'] != "") {echo $siteConfig['name'];} else {echo "Dropplets";} ?> | <?php echo $pageTitle; ?>">
    <?php if ($post['image'] != "") { ?>
    <meta name="twitter:image:src" content="<?php echo $post['image']; ?>">
    <?php } ?>
    <!-- Open Graph general (Facebook, Pinterest & Google+) -->
    <meta name="og:title" content="<?php if ($siteConfig['name'] != "") {echo $siteConfig['name'];} else {echo "Dropplets";} ?> | <?php echo $pageTitle; ?>">
    <?php if ($post['image'] != "") { ?>
    <meta name="og:image" content="<?php echo $post['image']; ?>">
    <?php } ?>
    <meta name="og:site_name" content="<?php if ($siteConfig['name'] != "") {echo $siteConfig['name'];} else {echo "Dropplets";} ?>">
    <meta name="og:type" content="website">
    
    <link type="text/css" rel="stylesheet" href="https://meyerweb.com/eric/tools/css/reset/reset.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $siteConfig['basePath'] ?>/templates/<?php echo $siteConfig['template'] ?>/static/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
</head>

<body>