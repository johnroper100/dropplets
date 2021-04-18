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
    <?php if ($postCount > 0) { ?>
        <h1><?php i18n("dashboard_all_post"); ?></h1>
    <?php
    }
    foreach ($allPosts as $post) {
    ?>
        <div class="post">
            <div class="postText">
                <h2 id="postTitle">
                    <a href="<?php echo $router->generate('post', ['id' => $post['_id']]); ?>"><?php echo $post['title']; ?></a>
                </h2>
                <?php i18n("dashboard_posted_by"); ?> <?php echo $post['author']; ?> - <?php echo strftime(i18n('dashboard_post_fulldate',false), $post['date']); ?>
                <br>
                <?php if ($post['draft'] == true) { ?>
                    <a href="<?php echo $router->generate('publish', ['id' => $post['_id']]); ?>" class="btn btn-sm"><?php i18n("dashboard_publish"); ?></a>
                <?php } else { ?>
                    <a href="<?php echo $router->generate('hide', ['id' => $post['_id']]); ?>" class="btn btn-sm"><?php i18n("dashboard_draft"); ?></a>
                <?php } ?>
                <a href="<?php echo $router->generate('editPost', ['id' => $post['_id']]); ?>" class="btn btn-sm btn-secondary"><?php i18n("dashboard_edit"); ?></a>
                <a href="<?php echo $router->generate('deletePost', ['id' => $post['_id']]); ?>" class="btn btn-sm btn-danger"><?php i18n("dashboard_delete"); ?></a>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<?php
require "footer.php";
?>