<?php
require "header.php";
?>
<h1 class="setupH1 setup">Your Blog Dashboard</h1>
<div style="text-align: center;">
    <a href="<?php echo $router->generate('write'); ?>" class="btn">Write A Blog Post</a>
    <a href="<?php echo $router->generate('settings'); ?>" class="btn btn-secondary">Edit The Site Settings</a>
    <a href="<?php echo $router->generate('logout'); ?>" class="btn btn-danger">Log Out</a>
</div>
<div style="margin-top: 35px;">
    <?php if ($postCount > 0) { ?>
        <h1>All Posts:</h1>
    <?php
    }
    foreach ($allPosts as $post) {
    ?>
        <div class="post">
            <?php if ($post['image'] != "") { ?><img src="<?php echo $post['image']; ?>"><?php } ?>
            <div class="postText">
                <h2 id="postTitle"><a href="<?php echo $router->generate('post', ['id' => $post['_id']]); ?>"><?php echo $post['title']; ?></a></h2>
                Posted by <?php echo $post['author']; ?> <?php echo date('F j, Y, g:i A', $post['date']); ?>
                <br>
                <?php if ($post['draft'] == true) { ?>
                    <a href="<?php echo $router->generate('publish', ['id' => $post['_id']]); ?>" class="btn btn-sm">Publish</a>
                <?php } else { ?>
                    <a href="<?php echo $router->generate('hide', ['id' => $post['_id']]); ?>" class="btn btn-sm">Make Draft</a>
                <?php } ?>
                <a href="<?php echo $router->generate('editPost', ['id' => $post['_id']]); ?>" class="btn btn-sm btn-secondary">Edit</a>
                <a href="<?php echo $router->generate('deletePost', ['id' => $post['_id']]); ?>" class="btn btn-sm btn-danger">Delete</a>
            </div>
        </div>
    <?php
    }
    ?>
</div>
<?php
require "footer.php";
?>