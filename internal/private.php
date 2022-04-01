<?php
require "header.php";
?>
<div class="row my-1">
    <div class="col-md-3"></div>
    <div class="col-md-6 text-center">
        <h1><?php i18n("private_title"); ?></h1>
        <form method="post" role="form" action="">
            <fieldset class="my-3 w-50 mx-auto">
                <label for="password" class="form-label"><?php i18n("private_password_legend"); ?></label>
                <input type="password" class="form-control" name="password" id="password" placeholder="<?php i18n("private_password_placeholder"); ?>" required="">
            </fieldset>
            <div class="row mx-auto w-50 text-center">
                <button class="btn btn-primary mb-3" type="submit"><?php i18n("private_submit"); ?></button>
            </div>
        </form>
        <div class="justify-content-center">
            <button type="button" class="btn btn-secondary w-25 mx-1" onclick="history.go(-1);">Go Back</button>
            <a type="button" class="btn btn-secondary w-25 mx-1" href="<?php echo $siteConfig['domain'] ?>">Go Home</a>
        </div>
    </div>
    <div class="col-md-3"></div>
</div>
<?php
require "footer.php";
?>