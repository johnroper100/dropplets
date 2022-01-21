<?php
require "header.php";
?>
<h1 class="setupH1 setup"><?php i18n("write_title"); ?></h1>
<form method="post" enctype="multipart/form-data" action="<?php if (isset($post['title'])) {
                                                                echo $router->generate('editPost', ['id' => $post['_id']]);
                                                            } else {
                                                                echo $router->generate('write');
                                                            } ?>">
    <fieldset>
        <input type="text" name="blogPostTitle" class="blogPostTitle" placeholder="<?php i18n("write_post_title_placeholder"); ?>" required value="<?php if (isset($post['title'])) {
                                                                                                                                                        echo $post['title'];
                                                                                                                                                    } ?>" />
        <input type="text" name="blogPostAuthor" class="blogPostAuthor" placeholder="<?php i18n("write_post_author_placeholder"); ?>" required value="<?php if (isset($post['author'])) {
                                                                                                                                                            echo $post['author'];
                                                                                                                                                        } ?>" />
        <input type="url" name="blogPostImageURL" class="blogPostImageURL" placeholder="<?php i18n("write_post_image_placeholder"); ?>" value="<?php if (isset($post['image'])) {
                                                                                                                                                    echo $post['image'];
                                                                                                                                                } ?>" />
        <input type="file" name="imageUpload" class="blogPostImage" id="imageUpload" />
        <input type="text" name="blogPostPassword" class="blogPostTitle" placeholder="<?php i18n("write_post_password_placeholder"); ?>" value="<?php if (isset($post['password'])) {
                                                                                                                                                    echo $post['password'];
                                                                                                                                                } ?>" />
        <textarea name="blogPostContent" id="blogPostContent" placeholder="<?php i18n("write_post_markdown_placeholder"); ?>"><?php if (isset($post['content'])) {
                                                                                                                                    echo $post['content'];
                                                                                                                                } ?></textarea>
    </fieldset>
    <?php if (isset($post['title'])) { ?>
        <input class="btn" type="submit" value="Save Edits" />
    <?php } else { ?>
        <input class="btn" type="submit" value="Save Post" />
    <?php } ?>
</form>
<div style="text-align: center; padding-top: 25px;">
    <a href="<?php echo $router->generate('dashboard'); ?>" class="btn btn-sm btn-secondary">Return To Dashboard</a>
</div>
<script>
    var simplemde = new SimpleMDE({
        element: document.getElementById("blogPostContent")
    });
</script>
<?php
require "footer.php";
?>