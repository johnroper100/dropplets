<?php
require "header.php";
?>
<div class="setup">
    <div class="setupHeader">
        <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span></a>
    </div>
    <h1 class="setupH1 setup">Time to write your prose</h1>
    <form method="post" action="<?php echo $router->generate('write'); ?>">
        <fieldset>
            <input type="text" name="blogPostTitle" class="blogPostTitle" placeholder="The post title" required />
            <input type="text" name="blogPostAuthor" class="blogPostAuthor" placeholder="The post author" required />
            <input type="text" name="blogPostImage" class="blogPostImage" placeholder="A featured image for this post" />
            <textarea name="blogPostContent" id="blogPostContent" placeholder="Write your post here, you can use Markdown"></textarea>
            <input type="password" name="blogPassword" placeholder="Management password" required />
        </fieldset>
        <input class="btn" type="submit" value="Publish New Post" />
    </form>
</div>
<script>
    var simplemde = new SimpleMDE({
        element: document.getElementById("blogPostContent")
    });
</script>
<?php
require "footer.php";
?>