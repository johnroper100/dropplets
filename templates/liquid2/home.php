<?php
require "header.php";
require_once "./SleekDB/src/Store.php";

use SleekDB\Store;
?>
<div class="row mb-5">
    <div class="col-12">
        <?php
        foreach ($allPosts as $post) {
        ?>
            <div class="card">
                <?php
                if ($post['image'] != "") {
                    if (is_numeric($post['image']) == TRUE) {
                        $databaseDirectory = "./siteDatabase";
                        $imageStore = new Store("images", $databaseDirectory);
                        $imageRecord = $imageStore->findById($post['image']);
                        echo '<img src="data:image/' . $imageRecord["type"] . ';base64,' . $imageRecord["base64"] . '" class="card-img-top">';
                    } else {
                        echo '<img src="' . $post['image'] . '" class="card-img-top">';
                    }
                }
                ?>
                <div class="card-body">
                    <a href="<?php echo $router->generate('post', ['id' => $post['_id']]); ?>" class="text-decoration-none text-dark">
                        <h3 class="card-title"><?php echo $post['title']; ?></h3>
                    </a>
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