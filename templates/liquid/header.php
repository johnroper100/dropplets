<!DOCTYPE html>
<html>

<head>
    <?php
    if ($siteConfig['headerInject'] != "") {
        echo base64_decode($siteConfig['headerInject']);
    }
    ?>

    <title><?php if ($siteConfig['name'] != "") {
                echo $siteConfig['name'];
            } else {
                echo "Dropplets";
            } ?> | <?php echo $pageTitle; ?></title>
    <link type="text/css" rel="stylesheet" href="https://meyerweb.com/eric/tools/css/reset/reset.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $siteConfig['basePath'] ?>/templates/<?php echo $siteConfig['template'] ?>/static/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
</head>

<body>