<?php
require "header.php";
?>
<div id="index">
    <header>
        <h1 id="siteTitle"><?php echo $siteConfig['name']; ?></h1>
    </header>
    <div class="posts">
        <?php
        foreach ($allPosts as $post) {
        ?>
            <div class="post">
                <h2 id="postTitle"><a href="<?php echo $router->generate('post', ['id' => $post['_id']]); ?>"><?php echo $post['title']; ?></a></h2>
                <span id="postSubtitle"><?php echo date('F j, Y, g:i a', $post['date']); ?></span>
                <div id="postContent"><?php echo $Extra->text(substr($post['content'], 0, 250) . "..."); ?></div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<div id="footer">
    <p class="footerText"><?php echo $siteConfig['footer']; ?></p>
</div>
<?php
require "footer.php";
?>