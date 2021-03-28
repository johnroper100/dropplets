<?php
require "header.php";
?>
<div class="setup">
    <div class="setupHeader">
        <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span></a>
    </div>
    <h1 class="setupH1 setup">Log Into Your Blog</h1>
    <form method="post" action="<?php echo $router->generate('login'); ?>">
        <fieldset>
            <legend>Type your password to update your blog:</legend>
            <input type="password" name="blogPassword" placeholder="Management password" required />
        </fieldset>
        <input class="btn" type="submit" value="Log In" />
    </form>
</div>
<?php
require "footer.php";
?>