<?php
require "header.php";
require_once "./SleekDB/src/Store.php";
use SleekDB\Store;
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
        <input type="text" name="blogPostLink" class="blogPostLink" placeholder="<?php i18n("write_post_link_placeholder"); ?>" value="<?php if (isset($post['SEF_URL'])) {
                                                                                                                                                            echo $post['SEF_URL'];
                                                                                                                                                        } ?>" />
        <input type="url" name="blogPostImageURL" class="blogPostImageURL" placeholder="<?php i18n("write_post_image_placeholder"); ?>" value="<?php if (isset($post['image'])) {
                                                                                                                                                        if (is_numeric($post['image']) == TRUE) {
                                                                                                                                                            $databaseDirectory = "./siteDatabase";
                                                                                                                                                            $imageStore = new Store("images", $databaseDirectory);
                                                                                                                                                            $imageRecord = $imageStore->findById($post['image']);
                                                                                                                                                            echo $imageRecord["url"];
                                                                                                                                                        }
                                                                                                                                                     } ?>" />
        <input type="file" name="imageUpload" class="blogPostImage form-control form-control-sm" id="imageUpload" />
        <?php if (isset($post['title'])) { ?>
        	<!-- Implement i18n functions for the text -->
            <label for="imageUpload">If you upload a file the existing image will be deleted! (10 MB Max)</label>
        <?php } else { ?>
            <label for="imageUpload">Choose a file to upload (10 MB Max)</label>
        <?php } ?>
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
    
	var uploadCheck = document.getElementById("imageUpload");
	uploadField.onchange = function (){
		if(this.files[0].size > 10485760){
			// Implement i18n functions for the text
			alert("Uploaded file exceeds max size of 10 MB");
			this.value = "";
		}
	}
</script>
<?php
require "footer.php";
?>