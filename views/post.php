<?php
require "header.php";
?>
<div id="postPage">
    <header>
        <a id="siteTitleLink" href="<?php echo $siteConfig['basePath']; ?>">➜</a>
    </header>
    <div id="postTitleDate">
        <h1 id="postTitle"><?php echo $post['title']; ?></h1>
        <span id="postSubtitle"><?php echo date('F j, Y, g:i a', $post['date']); ?></span>
    </div>
    <div id="postContent"><?php echo $Extra->text($post['content']); ?></div>
</div>
<div id="footer">
    <p class="footerText"><?php echo $siteConfig['footer']; ?></p>
</div>
<?php
require "footer.php";
?>