<?php

/*-----------------------------------------------------------------------------------*/
/* Save Submitted Settings
/*-----------------------------------------------------------------------------------*/

if($_POST['submit'] == "submit")
{
    // Get submitted setup values.
    $blog_email = $_POST['blog_email'];
    $blog_twitter = $_POST['blog_twitter'];
    $blog_url = $_POST['blog_url'];
    $blog_title = htmlspecialchars($_POST['blog_title']);
    $meta_description = htmlspecialchars($_POST['meta_description']);
    $intro_title = htmlspecialchars($_POST['intro_title']);
    $intro_text = htmlspecialchars($_POST['intro_text']);
    $password = sha1($_POST['password']);
    $header_inject = addslashes($_POST['header_inject']);
    $footer_inject = addslashes($_POST['footer_inject']);

    // Output submitted setup values.
    $config[] = "<?php";
    $config[] = "\$blog_email = '$blog_email';";
    $config[] = "\$blog_twitter = '$blog_twitter';";
    $config[] = "\$blog_url = '$blog_url';";
    $config[] = "\$blog_title = \"$blog_title\";";
    $config[] = "\$meta_description = \"$meta_description\";";
    $config[] = "\$intro_title = \"$intro_title\";";
    $config[] = "\$intro_text = \"$intro_text\";";
    $config[] = "\$password = '$password';";
    $config[] = "\$header_inject = '$header_inject';";
    $config[] = "\$footer_inject = '$footer_inject';";
    
    // Create the settings file.
    file_put_contents("../../dropplets/config/config-settings.php", implode("\n", $config));
    
    // Generate the "htaccess" file on initial setup only.
    if (!file_exists('../../.htaccess')) {
    
        // Parameters for the htaccess file.
        $htaccess[] = "# Pretty Permalinks";
        $htaccess[] = "RewriteRule ^(dashboard)($|/) - [L]";
        $htaccess[] = "RewriteRule ^(images)($|/) - [L]";
        $htaccess[] = "Options +FollowSymLinks -MultiViews";
        $htaccess[] = "RewriteEngine on";
        $htaccess[] = "RewriteCond %{REQUEST_URI} !index";
        $htaccess[] = "RewriteCond %{REQUEST_FILENAME} !-f";
        $htaccess[] = "RewriteRule ^(.*)$ index.php?filename=$1 [L]";
    
        // Generate the htaccess file.
        file_put_contents("../../.htaccess", implode("\n", $htaccess));
        
    }

    // Redirect
    header('Location: ' . '../../');
}

?>