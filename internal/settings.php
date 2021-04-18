<?php
require "header.php";
?>
<?php if ($siteConfig['name'] == "") { ?>
    <h1 class="setupH1 setup"><?php i18n("settings_blog_creation"); ?></h1>
<?php } else { ?>
    <h1 class="setupH1 setup"><?php i18n("settings_blog_edition"); ?></h1>
<?php } ?>
<form method="post" action="<?php echo $router->generate('settings'); ?>">
    <fieldset>
        <legend><?php i18n("settings_legend"); ?></legend>
        <label><?php i18n("settings_i18n"); ?></label>
        <select name="blogI18N" id="blogI18N" value="<?php echo $siteConfig['I18N']; ?>">
            <option value="en_US" selected="<?php echo ($siteConfig['I18N'] ===  'en_US' || empty($siteConfig['I18N']))?"selected":""; ?>">ENGLISH en_US</option>
            <option value="fr_FR" selected="<?php echo ($siteConfig['I18N'] ===  'fr_FR')?"selected":""; ?>">FRANCAIS fr_FR</option>
        </select>
        <label><?php i18n("settings_blog_name"); ?></label>
        <input type="text" name="blogName" placeholder="<?php i18n("settings_blog_name_placeholder"); ?>" required value="<?php echo $siteConfig['name']; ?>" />
        <label><?php i18n("settings_blog_info"); ?></label>
        <input type="text" name="blogInfo" placeholder="<?php i18n("settings_blog_info_placeholder"); ?>" value="<?php echo $siteConfig['info']; ?>" />
        <label><?php i18n("settings_footer_message"); ?></label>
        <input type="text" name="blogFooter" placeholder="<?php i18n("settings_footer_message_placeholder"); ?>" value="<?php echo $siteConfig['footer']; ?>" />
        <label><?php i18n("settings_header_inject"); ?></label>
        <input type="text" name="blogHeaderInject" placeholder="<?php i18n("settings_header_inject_placeholder"); ?>" value="<?php echo base64_decode($siteConfig['headerInject']); ?>" />
        <label><?php i18n("settings_template"); ?></label>
        <input type="text" name="blogTemplate" placeholder="<?php i18n("settings_template_placeholder"); ?>" required value="<?php echo $siteConfig['template']; ?>" />
        <label><?php i18n("settings_timezone"); ?></label>
        <input type="text" name="blogTimezone" placeholder="<?php i18n("settings_timezone_placeholder"); ?>" required value="<?php echo $siteConfig['timezone']; ?>" />
        <label><?php i18n("settings_basepath"); ?></label>
        <input type="text" name="blogBase" placeholder="<?php i18n("settings_basepath_placeholder"); ?>" value="<?php echo $siteConfig['basePath']; ?>" />
    </fieldset>
    <?php if (!isset($_SESSION['isAuthenticated'])) { ?>
        <fieldset>
            <?php if ($siteConfig['name'] == "") { ?>
                <legend><?php i18n("settings_password_legend"); ?></legend>
                <input type="password" name="blogPassword" placeholder="<?php i18n("settings_password_placeholder"); ?>" required />
            <?php } else { ?>
                <legend><?php i18n("settings_password_legend_update"); ?></legend>
                <input type="password" name="blogPassword" placeholder="<?php i18n("settings_password_placeholder_update"); ?>" required />
            <?php } ?>
        </fieldset>
    <?php }
    if ($siteConfig['name'] == "") { ?>
        <input class="btn" type="submit" value="<?php i18n("settings_submit_creation"); ?>" />
    <?php } else { ?>
        <input class="btn" type="submit" value="<?php i18n("settings_submit_update"); ?>" />
    <?php } ?>
</form>
<div style="text-align: center; padding-top: 25px;">
    <a href="<?php echo $router->generate('dashboard'); ?>" class="btn btn-sm btn-secondary"><?php i18n("settings_dashboard_return"); ?></a>
</div>
<?php
require "footer.php";
?>