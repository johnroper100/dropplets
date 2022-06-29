<?php
require "header.php";

use SleekDB\Store;
?>
<div class="row mb-4">
    <div class="col-12">
        <?php
        if (isset($post['image'])) {
            if (is_numeric($post['image']) == TRUE) {
                $databaseDirectory = "./siteDatabase";
                $imageStore = new Store("images", $databaseDirectory);
                $imageRecord = $imageStore->findById($post['image']);
                echo '<img src="' . $imageRecord["url"] . '" class="img-fluid rounded-3">';
            }
        }
        ?>
    </div>
</div>
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
    <div class="row mb-5">
        <div class="col-12">
            <button type="button" class="btn btn-primary" onclick="history.go(-1);">Go Back</button>
            <a type="button" class="btn btn-secondary" href="<?php echo $siteConfig['domain'] ?>">Go Home</a>
        </div>
    </div>
<?php
require "footer.php";
?>