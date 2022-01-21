<!doctype html>
<html lang="en">

<head>
    <?php
    if ($siteConfig['headerInject'] != "") {
        echo base64_decode($siteConfig['headerInject']);
    }
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="index, follow">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title><?php if ($siteConfig['name'] != "") {
                echo $siteConfig['name'];
            } else {
                echo "Dropplets";
            } ?> | <?php echo $pageTitle; ?></title>
    <!-- Search Engine -->
    <?php if ($siteConfig['image'] != "") { ?>
        <meta name="image" content="<?php echo $siteConfig['image']; ?>">
    <?php } ?>
    <!-- Schema.org for Google -->
    <meta itemprop="name" content="<?php if ($siteConfig['name'] != "") {
                                        echo $siteConfig['name'];
                                    } else {
                                        echo "Dropplets";
                                    } ?> | <?php echo $pageTitle; ?>">
    <?php if ($siteConfig['image'] != "") { ?>
        <meta itemprop="image" content="<?php echo $siteConfig['image']; ?>">
    <?php } ?>
    <!-- Twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php if ($siteConfig['name'] != "") {
                                            echo $siteConfig['name'];
                                        } else {
                                            echo "Dropplets";
                                        } ?> | <?php echo $pageTitle; ?>">
    <?php if ($siteConfig['image'] != "") { ?>
        <meta name="twitter:image:src" content="<?php echo $siteConfig['image']; ?>">
    <?php } ?>
    <!-- Open Graph general (Facebook, Pinterest & Google+) -->
    <meta name="og:title" content="<?php if ($siteConfig['name'] != "") {
                                        echo $siteConfig['name'];
                                    } else {
                                        echo "Dropplets";
                                    } ?> | <?php echo $pageTitle; ?>">
    <?php if ($siteConfig['info'] != "") { ?>
        <meta name="og:description" content="<?php echo $siteConfig['info']; ?>">
        <meta name="description" content="<?php echo $siteConfig['info']; ?>">
    <?php } ?>
    <?php if ($siteConfig['image'] != "") { ?>
        <meta name="og:image" content="<?php echo $siteConfig['image']; ?>">
    <?php } ?>
    <meta name="og:site_name" content="<?php if ($siteConfig['name'] != "") {
                                            echo $siteConfig['name'];
                                        } else {
                                            echo "Dropplets";
                                        } ?>">
    <meta name="og:type" content="website">
    <meta name="og:url" content="<?php echo "https://" . $_SERVER['SERVER_NAME']; ?>">
</head>

<body>
    <style>
        .container {
            border-top: 2px solid #40c057;
        }

        .card::after {
            content: '';
            border-left: 2px solid #40c057;
            position: absolute;
            height: 0;
            left: 0;
            top: 0;
        }
    </style>
    <div class="container">
        <div class="row mt-5 mb-5">
            <div class="col-12 text-center">
                <h3><?php echo $siteConfig['name']; ?></h3>
                <small class="text-muted"><?php echo $siteConfig['info']; ?></small>
            </div>
        </div>