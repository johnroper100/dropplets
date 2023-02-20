<?php
require "header.php";
setlocale(LC_ALL, i18n('locale',false));
?>
<h1 class="setupH1 setup"><?php i18n("dashboard_title"); ?></h1>
<div style="text-align: center;">
    <a href="<?php echo $router->generate('write'); ?>" class="btn"><?php i18n("dashboard_write_post"); ?></a>
    <a href="<?php echo $router->generate('settings'); ?>" class="btn btn-secondary"><?php i18n("dashboard_settings"); ?></a>
    <a href="<?php echo $router->generate('logout'); ?>" class="btn btn-danger"><?php i18n("dashboard_logout"); ?></a>
</div>
<div style="margin-top: 35px;">
    <?php if ($draftPostCount > 0) { ?>
        <h1><?php i18n("dashboard_draft_post"); ?></h1>
    <?php
    }
    foreach ($draftPosts as $dPost) {
    ?>
        <div class="post">
            <div class="postText">
                <h2 id="postTitle">
                    <a href="<?= empty($dPost['SEF_URL']) ? $router->generate('post', ['id' => $dPost['_id']]) : $router->generate('SEF_URL', ['SEF_URL' => $dPost['SEF_URL']]) ?>"><?php echo $dPost['title']; ?></a>
                </h2>
                <?php i18n("dashboard_posted_by"); ?> <?php echo $dPost['author']; ?> - <?php echo date(i18n('dashboard_post_fulldate',false), $dPost['date']); ?>
                <br>
                <a href="<?php echo $router->generate('publish', ['id' => $dPost['_id']]); ?>" class="btn btn-sm"><?php i18n("dashboard_publish"); ?></a>
                <a href="<?php echo $router->generate('editPost', ['id' => $dPost['_id']]); ?>" class="btn btn-sm btn-secondary"><?php i18n("dashboard_edit"); ?></a>
                <a href="<?php echo $router->generate('deletePost', ['id' => $dPost['_id']]); ?>" class="btn btn-sm btn-danger"><?php i18n("dashboard_delete"); ?></a>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<div style="margin-top: 35px;">
    <?php if ($publishedPostCount > 0) { ?>
        <h1><?php i18n("dashboard_published_post"); ?></h1>
    <?php
    }
    foreach ($publishedPosts as $pPost) {
    ?>
        <div class="post">
            <div class="postText">
                <h2 id="postTitle">
                    <a href="<?= empty($pPost['SEF_URL']) ? $router->generate('post', ['id' => $pPost['_id']]) : $router->generate('SEF_URL', ['SEF_URL' => $pPost['SEF_URL']]) ?>"><?php echo $pPost['title']; ?></a>
                </h2>
                <?php i18n("dashboard_posted_by"); ?> <?php echo $pPost['author']; ?> - <?php echo date(i18n('dashboard_post_fulldate',false), $pPost['date']); ?>
                <br>
                <a href="<?php echo $router->generate('hide', ['id' => $pPost['_id']]); ?>" class="btn btn-sm"><?php i18n("dashboard_draft"); ?></a>
                <a href="<?php echo $router->generate('editPost', ['id' => $pPost['_id']]); ?>" class="btn btn-sm btn-secondary"><?php i18n("dashboard_edit"); ?></a>
                <a href="<?php echo $router->generate('deletePost', ['id' => $pPost['_id']]); ?>" class="btn btn-sm btn-danger"><?php i18n("dashboard_delete"); ?></a>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<?php
require "footer.php";
?>