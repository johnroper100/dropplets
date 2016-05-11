<?php
require_once('dropplets/includes/pclzip.lib.php');
include('./dropplets/settings.php');
include('./dropplets/functions.php');

file_put_contents("update.zip", fopen("https://github.com/Circa75/dropplets/archive/master.zip", 'r'));

$archive = new PclZip('update.zip');

if ($archive->extract(PCLZIP_OPT_PATH, 'update') == 0) {
    die("Error while extracting the update: ".$archive->errorInfo(true)." please first try setting your directory permissions to either 775 or 777 and then if that does not work, make an issue on the Dropplets GitHub page.");
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

$message = sprintf("Your site at %s has been updated to use the latest version of Dropplets!", $blog_url);
$headers[] = "From: " . $blog_email;
$headers[] = "Reply-To: " . $blog_email;
$headers[] = "X-Mailer: PHP/" . phpversion();

mail($blog_email, $blog_title . " - Dropplets Has Been Updated!", $message, implode("\r\n", $headers));

header("Location: " . $blog_url);
?>
