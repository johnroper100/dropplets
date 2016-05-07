<?php
ini_set('display_errors', 'On');
require_once('dropplets/includes/pclzip.lib.php');

file_put_contents("update.zip", fopen("https://github.com/Circa75/dropplets/", 'r'));
$archive = new PclZip('update.zip');
if ($archive->extract(PCLZIP_OPT_PATH, 'update') == 0) {
    die("Error : ".$archive->errorInfo(true));
}
rename("update/dropplets", "dropplets");
rename("update/plugins", "plugins");
rename("update/templates/simple", "templates/simple");
rename("update/templates/blink", "templates/blink");
rename("update/templates/cards", "templates/cards");
rename("update/templates/chevrons", "templates/chevrons");
rename("update/templates/citizen", "templates/citizen");
rename("update/templates/puddle", "templates/puddle");
rename("update/templates/star", "templates/star");
rename("update/index.php", "index.php");
rename("update/README.md", "README.md");
rename("update/License", "License");
?>
