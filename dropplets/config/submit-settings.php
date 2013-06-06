<?php

session_start();

// File locations.
$settings_file = "config-settings.php";
$htaccess_file = "../../.htaccess";
$phpass_file   = '../plugins/phpass-0.3/PasswordHash.php';
// Get existing settings.
if (file_exists($settings_file)) {
    include($settings_file);
}
if (file_exists($phpass_file))
{
    include($phpass_file);
    $hasher  = new PasswordHash(8,FALSE);
}
function settings_format($name, $value) {
    return sprintf("\$%s = \"%s\";", $name, $value);
}

/*-----------------------------------------------------------------------------------*/
/* Save Submitted Settings
/*-----------------------------------------------------------------------------------*/

// Should allow this only on first install or after the user is authenticated
// but this doesn't quite work. So back to default.
if ($_POST["submit"] == "submit" && (!file_exists($settings_file) || isset($_SESSION['user'])))
{
    // Get submitted setup values.
    $blog_email = $_POST["blog_email"];
    $blog_twitter = $_POST["blog_twitter"];
    $blog_url = $_POST["blog_url"];
    $blog_title = htmlspecialchars($_POST["blog_title"]);
    $meta_description = htmlspecialchars($_POST["meta_description"]);
    $intro_title = htmlspecialchars($_POST["intro_title"]);
    $intro_text = htmlspecialchars($_POST["intro_text"]);

    // There must always be a $password, but it can be changed optionally in the
    // settings, so you might not always get it in $_POST.
    if (!isset($password) || !empty($_POST["password"])) {
        $password = $hasher->HashPassword($_POST["password"]);
    }

    if(!isset($header_inject)) {
        $header_inject = "";        
    }

    if(isset($_POST["header_inject"])) {
        $header_inject = addslashes($_POST["header_inject"]);
    }

    if(!isset($footer_inject)) {
        $footer_inject = "";
    }
    
    if(isset($_POST["footer_inject"])) {
        $footer_inject = addslashes($_POST["footer_inject"]);
    }

    // Get subdirectory
    $dir_arr = explode('dropplets/', $_SERVER['SCRIPT_NAME']);
    $dir = $dir_arr[0];

    // Output submitted setup values.
    $config[] = "<?php";
    $config[] = settings_format("blog_email", $blog_email);
    $config[] = settings_format("blog_twitter", $blog_twitter);
    $config[] = settings_format("blog_url", $blog_url);
    $config[] = settings_format("blog_title", $blog_title);
    $config[] = settings_format("meta_description", $meta_description);
    $config[] = settings_format("intro_title", $intro_title);
    $config[] = settings_format("intro_text", $intro_text);
    $config[] = settings_format("password", $password);
    $config[] = settings_format("header_inject", $header_inject);
    $config[] = settings_format("footer_inject", $footer_inject);
    
    // Create the settings file.
    file_put_contents($settings_file, implode("\n", $config));
    
    // Generate the .htaccess file on initial setup only.
    if (!file_exists($htaccess_file)) {
    
        // Parameters for the htaccess file.
        $htaccess[] = "# Pretty Permalinks";
        $htaccess[] = "RewriteRule ^(dashboard)($|/) - [L]";
        $htaccess[] = "RewriteRule ^(images)($|/) - [L]";
        $htaccess[] = "Options +FollowSymLinks -MultiViews";
        $htaccess[] = "RewriteEngine on";
        if (strlen($dir) > 1)
            $htaccess[] = "RewriteBase " . $dir;
        $htaccess[] = "RewriteCond %{REQUEST_URI} !index";
        $htaccess[] = "RewriteCond %{REQUEST_FILENAME} !-f";
        $htaccess[] = "RewriteRule ^(.*)$ index.php?filename=$1 [NC,QSA,L]";
    
        // Generate the .htaccess file.
        file_put_contents($htaccess_file, implode("\n", $htaccess));
    }

    // Redirect
    header("Location: " . $blog_url);
}

?>
