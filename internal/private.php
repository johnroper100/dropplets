<?php
require "header.php";
?>
<h1 class="setupH1 setup"><?php i18n("private_title"); ?></h1>
<form method="post" action="">
    <fieldset>
        <legend><?php i18n("private_password_legend"); ?></legend>
        <input type="password" name="password" placeholder="<?php i18n("private_password_placeholder"); ?>" required />
    </fieldset>
    <input class="btn" type="submit" value="<?php i18n("private_submit"); ?>" />
</form>
<?php
require "footer.php";
?>