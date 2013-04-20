<?php

/*-----------------------------------------------------------------------------------*/
/* Save Submitted Settings
/*-----------------------------------------------------------------------------------*/

// Get existing config for the dashboard.
if (file_exists('config-settings.php')) {
    include('config-settings.php');
    
    // Get the existing sha1 password.
    $current_password = $password;
}

if ($_POST['submit'] == "submit")
{
    // Get submitted setup values.
    $set_blog_email = $_POST['blog_email'];
    $set_blog_twitter = $_POST['blog_twitter'];
    $set_blog_url = $_POST['blog_url'];
    $set_blog_title = htmlspecialchars($_POST['blog_title']);
    $set_meta_description = htmlspecialchars($_POST['meta_description']);
    $set_intro_title = htmlspecialchars($_POST['intro_title']);
    $set_intro_text = htmlspecialchars($_POST['intro_text']);
    if (!empty($_POST['password'])) {
        $set_password = sha1($_POST['password']);
    } else {
        $set_password = $current_password;
    }
    $set_header_inject = addslashes($_POST['header_inject']);
    $set_footer_inject = addslashes($_POST['footer_inject']);

    // Output submitted setup values.
    $config[] = "<?php";
    $config[] = "\$blog_email = '$set_blog_email';";
    $config[] = "\$blog_twitter = '$set_blog_twitter';";
    $config[] = "\$blog_url = '$set_blog_url';";
    $config[] = "\$blog_title = \"$set_blog_title\";";
    $config[] = "\$meta_description = \"$set_meta_description\";";
    $config[] = "\$intro_title = \"$set_intro_title\";";
    $config[] = "\$intro_text = \"$set_intro_text\";";
    $config[] = "\$password = '$set_password';";
    $config[] = "\$header_inject = '$set_header_inject';";
    $config[] = "\$footer_inject = '$set_footer_inject';";
    
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
    header('Location: ' . $set_blog_url);
}

?>