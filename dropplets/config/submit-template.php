<?php

session_start();

/*-----------------------------------------------------------------------------------*/
/* Save Template Selection
/*-----------------------------------------------------------------------------------*/

// Get existing config.
include("config-settings.php");

if($_POST["submit"] == "submit" && isset($_SESSION['user']))
{
    // Get the submitted template.
    $template = $_POST["template"];

    // Output the submitted template value.
    $config[] = "<?php";
    $config[] = "\$template = \"$template\";";

    // Update the template config file.
    file_put_contents("../../dropplets/config/config-template.php", implode("\n", $config));

    // Redirect to the set blog url.
    header("Location: " . $blog_url);
}

?>