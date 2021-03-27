<?php
require "header.php";
?>
<div id="index">
    <header>
        <a href="<?php echo $router->generate('home'); ?>">
            <h1 id="siteTitle"><?php echo $siteConfig['name']; ?></h1>
        </a>
        <?php if ($page == 1) { ?><div id="siteInfo"><?php echo $siteConfig['info']; ?></div><?php } ?>
    </header>
    <div class="posts">
        <?php
        foreach ($allPosts as $post) {
        ?>
            <div class="post">
                <h2 id="postTitle"><a href="<?php echo $router->generate('post', ['id' => $post['_id']]); ?>"><?php echo $post['title']; ?></a></h2>
                <span id="postSubtitle"><?php echo date('F j, Y, g:i a', $post['date']); ?></span>
                <!--<div id="postContent"><?php echo $Extra->text(substr($post['content'], 0, 350) . "..."); ?></div>-->
            </div>
        <?php
        }
        ?>
        <div class="clearfix">
            <?php
            if ($page > 1) {
            ?>
                <a href="<?php echo $router->generate('posts', ['page' => $page - 1]); ?>" class="btn" style="float: left; text-align: left;">Newer Posts</a>
            <?php
            }
            if ($postCount > $limit && $skip + $limit <= $postCount) {
            ?>
                <a href="<?php echo $router->generate('posts', ['page' => $page + 1]); ?>" class="btn" style="float: right; text-align: right;">Older Posts</a>
            <?php
            }
            ?>
        </div>
    </div>
</div>
<div id="footer">
    <p class="footerText"><?php echo $siteConfig['footer']; ?></p>
</div>
<?php
require "footer.php";
?>