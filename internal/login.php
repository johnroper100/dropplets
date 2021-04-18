<?php
require "header.php";
?>
<h1 class="setupH1 setup"><?php i18n("login_title"); ?></h1>
<form method="post" action="<?php echo $router->generate('login'); ?>">
    <fieldset>
        <legend><?php i18n("login_password_legend"); ?></legend>
        <input type="password" name="blogPassword" placeholder="<?php i18n("login_password_placeholder"); ?>" required />
    </fieldset>
    <input class="btn" type="submit" value="<?php i18n("login_submit"); ?>" />
</form>
<?php
require "footer.php";
?>