<?php
require "header.php";
?>
<div class="row mb-5">
    <div class="col-12">
        <?php
        foreach ($allPosts as $post) {
        ?>
            <div class="card">
                <?php if ($post['image'] != "") { ?><img src="<?php echo $post['image']; ?>" class="card-img-top"><?php } ?>
                <div class="card-body">
                    <a href="<?php echo $router->generate('post', ['id' => $post['_id']]); ?>" class="text-decoration-none text-dark"><h3 class="card-title"><?php echo $post['title']; ?></h3></a>
                    <p class="card-text text-muted">Posted by <?php echo $post['author']; ?><br><?php echo date('F j, Y', $post['date']); ?></p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<?php
require "footer.php";
?>