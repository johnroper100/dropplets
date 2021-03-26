<?php
require "header.php";
?>
<div class="setup">
    <div class="setupHeader">
        <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span></a>
    </div>
    <h1 class="setupH1 setup">Let's create your blog</h1>
    <form method="post" action="setup">
        <fieldset>
            <legend>First, some details:</legend>
            <input type="text" name="blogName" placeholder="Enter your blog's name" required value="<?php echo $siteConfig['name']; ?>" />
            <input type="text" name="blogAuthor" placeholder="Enter your blog's default author" required />
            <input type="text" name="blogFooter" placeholder="Enter an optional footer message" value="<?php echo $siteConfig['footer']; ?>" />
        </fieldset>
        <fieldset>
            <legend>Last but not least, the password:</legend>
            <input type="password" name="blogPassword" placeholder="Choose a good password" required />
        </fieldset>
        <input class="btn" type="submit" value="Create Your Blog" />
    </form>
</div>
<?php
require "footer.php";
?>