<?php
require "header.php";
?>
<div id="index">
    <header>
        <a href="<?php echo $router->generate('home'); ?>">
            <h1 id="siteTitle"><?php echo $siteConfig['name']; ?></h1>
        </a>
        <div id="siteInfo"><?php echo $siteConfig['info']; ?></div>
    </header>
    <div class="posts">
        <?php
        foreach ($allPosts as $post) {
        ?>
            <div class="post">
                <?php if ($post['image'] != "") { ?><img src="<?php echo $post['image']; ?>"><?php } ?>
                <div class="postText">
                    <h2 id="postTitle"><a href="<?php echo $router->generate('post', ['id' => $post['_id']]); ?>"><?php echo $post['title']; ?></a></h2>
                    <span id="postSubtitle">Posted by <?php echo $post['author']; ?></span>
                    <span id="postSubtitle"><?php echo date('F j, Y, g:i a', $post['date']); ?></span>
                </div>
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