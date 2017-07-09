<?php
require_once('dropplets/includes/pclzip.lib.php');
include('./dropplets/settings.php');
include('./dropplets/functions.php');

file_put_contents("update.zip", fopen("https://github.com/Circa75/dropplets/archive/master.zip", 'r'));

$archive = new PclZip('update.zip');

if ($archive->extract(PCLZIP_OPT_PATH, 'update') == 0) {
    die("Error while extracting the update: ".$archive->errorInfo(true)." please first try setting your directory permissions to either 775 or 777 and then if that does not work, make an issue on the Dropplets GitHub page.");
}

rename("update/dropplets-master/dropplets", "dropplets");
rename("update/dropplets-master/plugins", "plugins");
rename("update/dropplets-master/templates/simple", "templates/simple");
rename("update/dropplets-master/templates/blink", "templates/blink");
rename("update/dropplets-master/templates/cards", "templates/cards");
rename("update/dropplets-master/templates/chevrons", "templates/chevrons");
rename("update/dropplets-master/templates/citizen", "templates/citizen");
rename("update/dropplets-master/templates/puddle", "templates/puddle");
rename("update/dropplets-master/templates/star", "templates/star");
rename("update/dropplets-master/index.php", "index.php");
rename("update/dropplets-master/update.php", "update.php");
rename("update/dropplets-master/README.md", "README.md");
rename("update/dropplets-master/License", "License");

$message = sprintf("Your site at %s has been updated to use the latest version of Dropplets!", $blog_url);
$headers[] = "From: " . $blog_email;
$headers[] = "Reply-To: " . $blog_email;
$headers[] = "X-Mailer: PHP/" . phpversion();

mail($blog_email, $blog_title . " - Dropplets Has Been Updated!", $message, implode("\r\n", $headers));

header("Location: " . $blog_url);
?>
