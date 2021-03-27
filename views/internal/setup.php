<?php
require "header.php";
?>
<div class="setup">
    <div class="setupHeader">
        <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span></a>
    </div>
    <?php if ($siteConfig['name'] == "") { ?>
        <h1 class="setupH1 setup">Let's create your blog</h1>
    <?php } else { ?>
        <h1 class="setupH1 setup">Edit your blog's settings</h1>
    <?php } ?>
    <form method="post" action="<?php echo $router->generate('setup'); ?>">
        <fieldset>
            <legend>First, some details:</legend>
            <label>Blog Name:</label>
            <input type="text" name="blogName" placeholder="Enter your blog's name" required value="<?php echo $siteConfig['name']; ?>" />
            <label>Blog Info:</label>
            <input type="text" name="blogInfo" placeholder="Enter an optional info message" value="<?php echo $siteConfig['info']; ?>" />
            <label>Footer Message:</label>
            <input type="text" name="blogFooter" placeholder="Enter an optional footer message" value="<?php echo $siteConfig['footer']; ?>" />
            <label>Site Template Name:</label>
            <input type="text" name="blogTemplate" placeholder="Enter the name of a template" required value="<?php echo $siteConfig['template']; ?>" />
            <label>Post Timezone:</label>
            <input type="text" name="blogTimezone" placeholder="Enter a timezone" required value="<?php echo $siteConfig['timezone']; ?>" />
            <label>Site Base Path:</label>
            <input type="text" name="blogBase" placeholder="Enter the base path" required value="<?php echo $siteConfig['basePath']; ?>" />
        </fieldset>
        <fieldset>
            <?php if ($siteConfig['name'] == "") { ?>
                <legend>Last but not least, the password:</legend>
                <input type="password" name="blogPassword" placeholder="Choose a good password" required />
            <?php } else { ?>
                <legend>Type your password to update your blog:</legend>
                <input type="password" name="blogPassword" placeholder="Management password" required />
            <?php } ?>
        </fieldset>
        <?php if ($siteConfig['name'] == "") { ?>
            <input class="btn" type="submit" value="Create Your Blog" />
        <?php } else { ?>
            <input class="btn" type="submit" value="Update Your Blog" />
        <?php } ?>
    </form>
</div>
<?php
require "footer.php";
?>