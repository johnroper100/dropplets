<?php
require "header.php";
?>
<h1 class="setupH1 setup">Time to write your prose</h1>
<form method="post" action="<?php echo $router->generate('write'); ?>">
    <fieldset>
        <input type="text" name="blogPostTitle" class="blogPostTitle" placeholder="The post title" required />
        <input type="text" name="blogPostAuthor" class="blogPostAuthor" placeholder="The post author" required />
        <input type="url" name="blogPostImage" class="blogPostImage" placeholder="A featured image for this post" />
        <textarea name="blogPostContent" id="blogPostContent" placeholder="Write your post here, you can use Markdown"></textarea>
    </fieldset>
    <input class="btn" type="submit" value="Publish New Post" />
</form>
<div style="text-align: center; padding-top: 25px;">
    <a href="<?php echo $router->generate('dashboard'); ?>" class="btn btn-sm">Return To Dashboard</a>
</div>
<script>
    var simplemde = new SimpleMDE({
        element: document.getElementById("blogPostContent")
    });
</script>
<?php
require "footer.php";
?>