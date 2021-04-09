<!doctype html>
<html lang="en">

<head>
    <?php
    if ($siteConfig['headerInject'] != "") {
        echo base64_decode($siteConfig['headerInject']);
    }
    ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <title><?php if ($siteConfig['name'] != "") {
                echo $siteConfig['name'];
            } else {
                echo "Dropplets";
            } ?> | <?php echo $pageTitle; ?></title>
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