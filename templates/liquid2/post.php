<?php
require "header.php";
?>
<?php if ($post['image'] != "") { ?>
    <div class="row mb-4">
        <div class="col-12">
            <img src="<?php echo $post['image']; ?>" class="img-fluid">
        </div>
    </div>
<?php } ?>
<div class="row mt-5 mb-4">
    <div class="col-12">
        <h1 class="display-6"><?php echo $post['title']; ?></h1>
        <h5 class="text-muted"><?php echo $post['author']; ?> - <?php echo date('F j, Y', $post['date']); ?></h5>
    </div>
</div>
<div class="row mb-5">
    <div class="col-12">
        <?php echo $Extra->text($post['content']); ?>
    </div>
</div>
<?php
require "footer.php";
?>