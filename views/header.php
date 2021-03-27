<!DOCTYPE html>
<html>

<head>
    <title><?php if ($siteConfig['name'] != "") {
                echo $siteConfig['name'];
            } else {
                echo "Dropplets";
            } ?> | <?php echo $pageTitle; ?></title>
    <link type="text/css" rel="stylesheet" href="https://meyerweb.com/eric/tools/css/reset/reset.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $siteConfig['basePath'] ?>/static/css/style.css">
</head>

<body>