<!DOCTYPE html>
<html>
<?php
    // Create the required .htaccess if it dosen't already exist
    if (!file_exists(".htaccess")) {
        $htaccess = fopen(".htaccess", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions.");
        $htaccess_content = "<IfModule mod_rewrite.c>\n\tRewriteEngine On\n\tRewriteBase /\n\tRewriteCond %{REQUEST_FILENAME} !-f\n\tRewriteCond %{REQUEST_FILENAME} !-d\n\tRewriteCond %{REQUEST_FILENAME} !-l\n\tRewriteRule .* index.php [L,QSA]\n</IfModule>";
        fwrite($htaccess, $htaccess_content);
        fclose($htaccess);
    }

    // Get the url parameters
    $URI = parse_url($_SERVER['REQUEST_URI']);
    $URI_parts = explode('/', $URI['path']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (test_input($_POST["form"]) == "setup") {
            $config = fopen("config.ini", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions.");
            $config_content = "hi";
            fwrite($config, $config_content);
            fclose($config);
        }
        header("Location: /");
        exit;
    } else {
        // If the config file exists, show the blog. If not, show the setup page
        if ($URI_parts[1] != 'setup') {
            if (file_exists("config")) {
                $config = parse_ini_file("config");
                exit;
            } else {
                header("Location: /setup");
                exit;
            }
        } else {
            if (!file_exists("config.ini")) {
                ?>
                <head>
                    <title>Dropplets | Setup</title>
                </head>
                <body>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="text" name="blogName" placeholder="Blog Name"><br>
                        <input type="text" name="authorName" placeholder="Author Name"><br>
                        <input type="hidden" name="form" value="setup">
                        <input type="submit" value="Submit">
                    </form>
                </body>
                <?php
            } else {
                header("Location: /");
                exit;
            }
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
</html>