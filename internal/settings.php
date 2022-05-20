<?php
require "header.php";
?>
<div class="row my-1">
    <div class="col-md-3"></div>
    <div class="col-md-6 text-center">
        <?php if ($siteConfig['name'] == "") { ?>
            <h1><?php i18n("settings_blog_creation"); ?></h1>
            <h3><?php i18n("settings_first_time"); ?></h3>
        <?php } else { ?>
            <h1><?php i18n("settings_blog_edition"); ?></h1>
            <h3><?php i18n("settings_welcome_back"); ?></h3>
        <?php } ?>
    </div>
    <div class="col-md-3"></div>
</div>
<div class="row my-1">
    <div class="col-md-3"></div>
    <div class="col-md-6">
        <form method="post" action="<?php echo $router->generate('settings'); ?>">
            <fieldset class="my-3">
                <label><?php i18n("settings_i18n"); ?></label>
                <select class="form-select" name="blogI18N" id="blogI18N" required value="<?php echo $siteConfig['I18N']; ?>">
                    <option value="en_US" <?php echo ($siteConfig['I18N'] === 'en_US' || empty($siteConfig['I18N']))?"selected":""; ?>>English</option>
                    <option value="fr_FR" <?php echo ($siteConfig['I18N'] === 'fr_FR')?"selected":""; ?>>Francais</option>
                </select>
                <label><?php i18n("settings_blog_name"); ?></label>
                <input class="form-control" type="text" name="blogName" placeholder="<?php i18n("settings_blog_name_placeholder"); ?>" required value="<?php echo $siteConfig['name']; ?>" />
                <label><?php i18n("settings_blog_info"); ?></label>
                <input class="form-control" type="text" name="blogInfo" placeholder="<?php i18n("settings_blog_info_placeholder"); ?>" value="<?php echo $siteConfig['info']; ?>" />
                <label><?php i18n("settings_blog_domain"); ?></label>
                <input class="form-control" type="url" name="blogDomain" placeholder="<?php echo $_SERVER['REQUEST_SCHEME']."://" . $_SERVER['HTTP_HOST'] ?>" required value="<?php echo $siteConfig['domain']; ?>" />
                <label><?php i18n("settings_blog_OGImage"); ?></label>
                <input class="form-control" type="text" name="blogOGImage" placeholder="<?php i18n("settings_blog_OGImage_placeholder"); ?>" value="<?php echo $siteConfig['OGImage']; ?>" />
                <label><?php i18n("settings_footer_message"); ?></label>
                <input class="form-control" type="text" name="blogFooter" placeholder="<?php i18n("settings_footer_message_placeholder"); ?>" value="<?php echo $siteConfig['footer']; ?>" />
                <label><?php i18n("settings_header_inject"); ?></label>
                <input class="form-control" type="text" name="blogHeaderInject" placeholder="<?php i18n("settings_header_inject_placeholder"); ?>" value="<?php echo base64_decode($siteConfig['headerInject']); ?>" />
                <label><?php i18n("settings_template"); ?></label>
                <input class="form-control" type="text" name="blogTemplate" placeholder="<?php i18n("settings_template_placeholder"); ?>" required value="<?php echo $siteConfig['template']; ?>" />
                <label><?php i18n("settings_posts_per_page"); ?></label>
                <input class="form-control" type="number" name="blogPostsPerPage" min="1" placeholder="5" required value="<?php echo $siteConfig['postsPerPage']; ?>" />
                <label><?php i18n("settings_timezone"); ?></label>
                <input class="form-control" type="text" name="blogTimezone" placeholder="<?php i18n("settings_timezone_placeholder"); ?>" required value="<?php echo $siteConfig['timezone']; ?>" />
                <label><?php i18n("settings_basepath"); ?></label>
                <input class="form-control" type="text" name="blogBase" placeholder="<?php i18n("settings_basepath_placeholder"); ?>" value="<?php echo $siteConfig['basePath']; ?>" />
            </fieldset>
            <?php if (!isset($_SESSION['isAuthenticated'])) { ?>
                <fieldset class="my-3">
                    <?php if ($siteConfig['name'] == "") { ?>
                        <legend><?php i18n("settings_password_legend"); ?></legend>
                        <input class="form-control" type="password" name="blogPassword" placeholder="<?php i18n("settings_password_placeholder"); ?>" required />
                    <?php } else { ?>
                        <legend><?php i18n("settings_password_legend_update"); ?></legend>
                        <input class="form-control" type="password" name="blogPassword" placeholder="<?php i18n("settings_password_placeholder_update"); ?>" required />
                    <?php } ?>
                </fieldset>
            <?php } ?>
            <div class="row mx-5">
                <div class="col-md-3"></div>
                <div class="col-md-6 text-center">
                <?php
                if ($siteConfig['name'] == "") { ?>
                    <button class="btn-lg btn-primary mb-3" type="submit"><?php i18n("settings_submit_creation"); ?></button>
                <?php } else { ?>
                    <button class="btn-lg btn-primary mb-3" type="submit"><?php i18n("settings_submit_update"); ?></button>
                <?php } ?>
                    <a type="button" class="btn-sm btn-secondary" href="<?php echo $router->generate('dashboard'); ?>"><?php i18n("settings_dashboard_return"); ?></a>
                </div>
                <div class="col-md-3"></div>
            </div>
        </form>
    </div>
    <div class="col-md-3"></div>
<?php
require "footer.php";
?>