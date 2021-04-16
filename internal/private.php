<?php
require "header.php";
?>
<h1 class="setupH1 setup">Private Post</h1>
<form method="get" action="">
    <fieldset>
        <legend>Type your password to access private post:</legend>
        <input type="password" name="password" placeholder="Post password" required />
    </fieldset>
    <input class="btn" type="submit" value="Unlock" />
</form>
<?php
require "footer.php";
?>