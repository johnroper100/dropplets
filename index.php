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
            if (!file_exists("config.ini")) {
                if(isset($_POST["blogName"]) and isset($_POST["blogAuthor"]) and isset($_POST["blogPassword"])){
                    $password_hash = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);
                    $config_content = "blogName=".test_input($_POST["blogName"])."\nblogAuthor=".test_input($_POST["blogAuthor"])."\nblogPassword=".$password_hash;
                    $config = fopen("config.ini", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions.");
                    fwrite($config, $config_content);
                    fclose($config);
                    header("Location: /");
                }
            } else {
                echo("Setup has already been completed!");
            }
        } else {
            echo("The form could not be submitted. Please try again later.");
        }
    } else {
        // If the config file exists, show the blog. If not, show the setup page
        if ($URI_parts[1] and $URI_parts[1] == 'setup') {
            if (!file_exists("config.ini")) {
                ?>
                <head>
                    <title>Dropplets | Setup</title>
                    <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                    <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/setup.css" />
                </head>
                <body>
                    <img src="https://rawgit.com/johnroper100/dropplets/2.0/logo.svg" class="headerLogo" />
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="text" name="blogName" placeholder="Blog Name:" /><br>
                        <input type="text" name="blogAuthor" placeholder="Author Name:" /><br>
                        <input type="password" name="blogPassword" placeholder="Management Password:" /><br>
                        <input type="hidden" name="form" value="setup">
                        <input type="submit" value="Submit">
                    </form>
                </body>
                <?php
            } else {
                echo("Setup has already been completed!");
            }
        } else {
            if (file_exists("config.ini")) {
                $config = parse_ini_file("config.ini");
                ?>
                <head>
                    <title><?php echo($config['blogName']) ?> | Home</title>
                    <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                    <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/main.css" />
                </head>
                <body>
                <h1>hi</h1>
                </body>
                <?php
                echo("you have made it home");
            } else {
                header("Location: /setup");
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