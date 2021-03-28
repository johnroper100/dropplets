<?php
require "header.php";
?>
<h1 class="setupH1 setup">Your Blog Dashboard</h1>
<div style="text-align: center;">
    <a href="<?php echo $router->generate('write'); ?>" class="btn">Write A Blog Post</a>
    <a href="<?php echo $router->generate('settings'); ?>" class="btn">Edit The Site Settings</a>
    <a href="<?php echo $router->generate('logout'); ?>" class="btn">Log Out</a>
</div>
<?php
require "footer.php";
?>