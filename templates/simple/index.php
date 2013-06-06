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
        <link rel="stylesheet" href="<?php echo $template_dir_url ?>subdiv.css">

        <link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>

        <link rel="shortcut icon" href="<?php echo $blog_url ?>/dropplets/style/images/favicon.png">

        <?php echo stripslashes($header_inject) ?>
    </head>

    <body>
        <?php echo $content ?>

        <?=(isset($pagination) && (isset($infinite_scroll) && $infinite_scroll == "off") ? $pagination : "") ?>

        <?php echo $powered_by ?>

        <script src="http://code.jquery.com/jquery-1.9.0.js"></script>
        <script>
            var infinite = <?=($infinite_scroll !== "off" && $pagination_on_off !== "off") ? "true;" : "false;";?>
            var next_page = 2;
            var loading = false;
            var no_more_posts = false;
            $(function() {
                function load_next_page() {
                    loading = true;

                    $.ajax({
                        url: "?page=" + next_page,
                        success: function (res) {
                            next_page++;
                            var result = $.parseHTML(res);
                            var articles = $(result).filter(function() {
                                return $(this).is('article');
                            });
                            if (articles.length < 1) {
                                no_more_posts = true;
                            }  else {
                                $('body').append(articles.slice(1));
                            }
                            loading = false;
                        }
                    });
                }

                $(window).scroll(function() {
                    var when_to_load = $(window).scrollTop() * 0.32;
                    if (infinite && (loading != true && !no_more_posts) && $(window).scrollTop() + when_to_load > ($(document).height()- $(window).height() ) ) {
                        setTimeout(load_next_page,500);
                    }
                });
            });
        </script>

        <?php echo stripslashes($footer_inject) ?>
    </body>
</html>
