<!DOCTYPE html>
<html>
<?php
    if (file_exists("config.php")) {
        include 'config.php';
    } else {
        $blogName = NULL;
        $blogAuthor = NULL;
        $blogCopyright = NULL;
        $blogPassword = NULL;
    }
    // Create the required .htaccess if it dosen't already exist.
    if (!file_exists(".htaccess")) {
        $htaccess = fopen(".htaccess", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions.");
        $htaccess_content = "<IfModule mod_rewrite.c>\n\tRewriteEngine On\n\tRewriteBase /\n\tRewriteCond %{REQUEST_FILENAME} !-f\n\tRewriteCond %{REQUEST_FILENAME} !-d\n\tRewriteCond %{REQUEST_FILENAME} !-l\n\tRewriteRule .* [L,QSA]\n</IfModule>";
        fwrite($htaccess, $htaccess_content);
        fclose($htaccess);
    }
    if (!file_exists("posts")) {
        mkdir("posts");
    }
    // Get the url parameters.
    $URI = parse_url($_SERVER['REQUEST_URI']);
    $URI_parts = explode('/', $URI['path']);
    // If a form is submitted, process it. Otherwise, show the main web page.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Setup form submitted, create config.php.
        if (test_input($_POST["form"]) == "setup") {
            // Check that the form items are there.
            if(isset($_POST["blogName"]) and isset($_POST["blogAuthor"]) and isset($_POST["blogCopyright"]) and isset($_POST["blogPassword"])){
                // If the config does not exist, create it. If it does, update it.
                if (!file_exists("config.php")) {
                    $password_hash = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);
                    $config_content = "<?php\n\$blogName='".test_input($_POST["blogName"])."';\n\$blogAuthor='".test_input($_POST["blogAuthor"])."';\n\$blogCopyright='".test_input($_POST["blogCopyright"])."';\n\$blogPassword='".$password_hash."';\n?>";
                } else {
                    if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                        $config_content = "<?php\n\$blogName='".test_input($_POST["blogName"])."';\n\$blogAuthor='".test_input($_POST["blogAuthor"])."';\n\$blogCopyright='".test_input($_POST["blogCopyright"])."';\n\$blogPassword='".$blogPassword."'\n?>";
                    } else {
                        echo("Management password not correct!");
                        exit;
                    }
                }
            }
            $config = fopen("config.php", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions.");
            fwrite($config, $config_content);
            fclose($config);
            header("Location: /");
        } else if (test_input($_POST["form"]) == "post") {
            if (file_exists("config.php")) {
                if(isset($_POST["blogPostTitle"]) and isset($_POST["blogPostContent"]) and isset($_POST["blogPassword"])){
                    if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                        $post_content = "<?php\n\$postTitle='".test_input($_POST["blogPostTitle"])."';\n\$postContent='".test_input($_POST["blogPostContent"])."';\n\$postDate='".date("F jS, Y")."';\n?>";
                        $k = 1;
                        while(!$result){
                            if(!file_exists("posts/post$k.php"))
                                $result = "post$k.php";
                            $k++;
                        }
                        $post = fopen("posts/".$result, 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions.");
                        fwrite($post, $post_content);
                        fclose($post);
                        header("Location: /");
                    } else {
                        echo("Management password not correct!");
                        exit;
                    }
                }
            } else {
                header("Location: setup");
            }
        } else {
            echo("The form could not be submitted. Please try again later.");
        }
    } else {
        // If the url is setup, check for config and then show the setup page.
        if ($URI_parts[1] and $URI_parts[1] == 'setup') { ?>
            <head>
                <title>Dropplets | Setup</title>
                <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/setup.css" />
            </head>
            <body>
                <img src="https://rawgit.com/johnroper100/dropplets/2.0/logo.svg" class="headerLogo" />
                <form method="post" action="setup">
                    <input type="text" name="blogName" placeholder="Blog Name:" required value="<?php echo($blogName); ?>"/>
                    <input type="text" name="blogAuthor" placeholder="Author Name:" required value="<?php echo($blogAuthor); ?>" />
                    <input type="text" name="blogCopyright" placeholder="Copyright Message:" required value="<?php echo($blogCopyright); ?>" />
                    <input type="password" name="blogPassword" placeholder="Management Password:" required />
                    <input type="hidden" name="form" value="setup" required />
                    <input class="btn" type="submit" value="Finish Setup" />
                </form>
            </body>
        <?php } else if ($URI_parts[1] and $URI_parts[1] == 'post') {
            if (file_exists("config.php")) { ?>
                <head>
                    <title><?php echo($blogName); ?> | New Post</title>
                    <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                    <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/setup.css" />
                </head>
                <body>
                    <img src="https://rawgit.com/johnroper100/dropplets/2.0/logo.svg" class="headerLogo" />
                    <form method="post" action="post">
                        <input type="text" name="blogPostTitle" placeholder="Post Title:" required />
                        <textarea name="blogPostContent" placeholder="Post Content:" required></textarea>
                        <input type="password" name="blogPassword" placeholder="Management Password:" required />
                        <input type="hidden" name="form" value="post" required />
                        <input class="btn" type="submit" value="Publish New Post" />
                    </form>
                </body>
                
            <?php } else {
                header("Location: setup");
            }
        } else if ($URI_parts[1] and $URI_parts[1] == 'version') {
            echo("Dropplets v2.0 Beta - Licensed Under the GPL 3.0 License");
        } else {
            // If the config exists, read it and display the blog.
            if (file_exists("config.php")) { ?>
                <head>
                    <title><?php echo($blogName); ?> | Home</title>
                    <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                    <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/main.css" />
                </head>
                <body>
                <h1><?php echo($blogName); ?></h1>
                <ul>
                <?php
                $posts = glob('posts/*.{php}', GLOB_BRACE);
                foreach($posts as $post) {
                  include $post;
                  echo("<li>".$postTitle." - ".$postDate."</li>");
                }
                ?>
                </ul>
                </body>
            <?php } else {
                header("Location: setup");
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
