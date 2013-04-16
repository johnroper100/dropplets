<?php

/*-----------------------------------------------------------------------------------*/
/* Save Submitted Content
/*-----------------------------------------------------------------------------------*/

if($_POST['submit'] == "submit")
{
    // Get Stuff
    $blog_email = $_POST['blog_email'];
    $blog_twitter = $_POST['blog_twitter'];
    $blog_url = $_POST['blog_url'];
    $blog_title = htmlspecialchars($_POST['blog_title']);
    $meta_description = htmlspecialchars($_POST['meta_description']);
    $intro_title = htmlspecialchars($_POST['intro_title']);
    $intro_text = htmlspecialchars($_POST['intro_text']);
    $password = $_POST['password'];
    $tracking_code = addslashes($_POST['tracking_code']);

    // Output Stuff
    $config[] = "<?php";
    $config[] = "\$blog_email = '$blog_email';";
    $config[] = "\$blog_twitter = '$blog_twitter';";
    $config[] = "\$blog_url = '$blog_url';";
    $config[] = "\$blog_title = \"$blog_title\";";
    $config[] = "\$meta_description = \"$meta_description\";";
    $config[] = "\$intro_title = \"$intro_title\";";
    $config[] = "\$intro_text = \"$intro_text\";";
    $config[] = "\$password = '$password';";
    $config[] = "\$tracking_code = '$tracking_code';";

    // Put Stuff
    file_put_contents("../../dropplets/config/config-settings.php", implode("\n", $config));

    // Redirect
    header('Location: ' . '../../');
}

?>