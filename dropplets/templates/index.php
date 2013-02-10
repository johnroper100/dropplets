<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        
        <title><?php echo $site_title ?></title>
        
        <meta name="description" content="<?php echo $site->meta_description ?>">
        <meta name="author" content="<?php echo $site->author->name ?>">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="<?php echo $site->url ?>/dropplets/styles/style.css">
        <link rel="stylesheet" href="<?php echo $site->url ?>/dropplets/styles/subdiv.css">
        
        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>
        
        <link rel="shortcut icon" href="<?php echo $site->url ?>/favicon.png">
    </head>
    
    <body>
        <?php echo $content ?>
        
        <script type="text/javascript">
          var _gauges = _gauges || [];
          (function() {
            var t   = document.createElement('script');
            t.type  = 'text/javascript';
            t.async = true;
            t.id    = 'gauges-tracker';
            t.setAttribute('data-site-id', '503c0135f5a1f5014d000001');
            t.src = '//secure.gaug.es/track.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(t, s);
          })();
        </script>
    </body>
</html>
