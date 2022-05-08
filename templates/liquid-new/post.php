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
                echo '<img src="data:image/' . $imageRecord["type"] . ';base64,' . $imageRecord["base64"] . '" class="img-fluid">';
            } else {
                echo '<img src="' . $post['image'] . '" class="img-fluid">';
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
<?php
require "footer.php";
?>