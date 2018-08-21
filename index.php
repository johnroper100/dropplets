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
            if(isset($_POST["blogName"]) and isset($_POST["blogAuthor"]) and isset($_POST["blogCopyright"]) and isset($_POST["blogPassword"]) and isset($_POST["blogStyleType"])){
                // Get the stylesheet link (placed here to be used in both the if config.php and if not)
                if (test_input($_POST["blogStyleType"]) == "default") {
                    $blogStyleSheet = "https://rawgit.com/johnroper100/dropplets/2.0/main.css";
                    $blogPostStyleSheet = "https://rawgit.com/johnroper100/dropplets/2.0/main.css";
                } else {
                    $blogStyleSheet = test_input($_POST["blogStyleSheet"]);
                    $blogPostStyleSheet = test_input($_POST["blogPostStyleSheet"]);
                }
                // If the config does not exist, create it. If it does, update it.
                if (!file_exists("config.php")) {
                    $password_hash = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);
                    
                    $config_content = "<?php\n\$blogName='".test_input($_POST["blogName"])."';\n\$blogAuthor='".test_input($_POST["blogAuthor"])."';\n\$blogCopyright='".test_input($_POST["blogCopyright"])."';\n\$blogPassword='".$password_hash."';\n\$blogStyleType='".test_input($_POST["blogStyleType"])."';\n\$blogStyleSheet='".$blogStyleSheet."';\n\$blogPostStyleSheet='".$blogPostStyleSheet."';\n\$headerInject='';\n\$footerInject='';\n?>";
                } else {
                    if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                        $config_content = "<?php\n\$blogName='".test_input($_POST["blogName"])."';\n\$blogAuthor='".test_input($_POST["blogAuthor"])."';\n\$blogCopyright='".test_input($_POST["blogCopyright"])."';\n\$blogPassword='".$blogPassword."';\n\$blogStyleType='".test_input($_POST["blogStyleType"])."';\n\$blogStyleSheet='".$blogStyleSheet."';\n\$blogPostStyleSheet='".$blogPostStyleSheet."';\n\$headerInject='".$headerInject."';\n\$footerInject='".$footerInject."';\n?>";
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
                if(isset($_POST["blogPostTitle"]) and isset($_POST["blogPostContent"]) and isset($_POST["blogPassword"]) and isset($_POST["blogPostStyleType"])){
                    if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                        if (test_input($_POST["blogPostStyleType"]) == "default") {
                            $postStyleSheet = $blogPostStyleSheet;
                        } else {
                            $postStyleSheet = test_input($_POST["blogPostStyleSheet"]);
                        }
                        $post_content = "<?php\n\$postTitle='".test_input($_POST["blogPostTitle"])."';\n\$postContent='".$_POST["blogPostContent"]."';\n\$postDate='".date("F jS, Y")."';\n\$postStyleSheet='".$postStyleSheet."';\n?>";
                        $k = 1;
                        $result = NULL;
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
        } else if (test_input($_POST["form"]) == "update") {
            if (file_exists("config.php")) {
                if(isset($_POST["blogPassword"])){
                    if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                        file_put_contents("index.php", fopen("https://rawgit.com/johnroper100/dropplets/2.0/index.php", 'r'));
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
        if ($URI_parts[1] and $URI_parts[1] == 'setup') { 
            if (!isset($blogStyleType)) { $blogStyleType = 'default'; }
        ?>
            <head>
                <title>Dropplets | Setup</title>
                <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/setup.css" />
            </head>
            <body>
                <a href="https://github.com/johnroper100/dropplets/tree/2.0"><img src="https://rawgit.com/johnroper100/dropplets/2.0/logo.svg" class="headerLogo" /></a>
                <form method="post" action="setup">
                    <input type="text" name="blogName" placeholder="Blog Name:" required value="<?php echo($blogName); ?>"/>
                    <input type="text" name="blogAuthor" placeholder="Author Name:" required value="<?php echo($blogAuthor); ?>" />
                    <input type="text" name="blogCopyright" placeholder="Copyright Message:" required value="<?php echo($blogCopyright); ?>" />
                    <select name="blogStyleType" onchange="showStyleInput(this);">
                        <option value="default" <?php if ($blogStyleType == 'default') { ?>selected<?php } ?>>Use Default Stylesheets</option>
                        <option value="custom" <?php if ($blogStyleType == 'custom') { ?>selected<?php } ?>>Use Custom Stylesheets</option>
                    </select>
                    <input id="blogStyleSheet" type="url" name="blogStyleSheet" placeholder="Custom Stylesheet Link:" value="<?php if ($blogStyleType == 'custom') { echo($blogStyleSheet); } ?>" />
                    <input id="blogPostStyleSheet" type="url" name="blogPostStyleSheet" placeholder="Custom Post Stylesheet Link:" value="<?php if ($blogStyleType == 'custom') { echo($blogPostStyleSheet); } ?>" />
                    <input type="password" name="blogPassword" placeholder="Management Password:" required />
                    <input type="hidden" name="form" value="setup" required />
                    <input class="btn" type="submit" value="Finish Setup" />
                </form>
                <script>
                    <?php if ($blogStyleType == 'default') { ?>
                        document.getElementById("blogStyleSheet").style.display = "none";
                        document.getElementById("blogPostStyleSheet").style.display = "none";
                    <?php } ?>
                    function showStyleInput(that) {
                        if (that.value == "custom") {
                            document.getElementById("blogStyleSheet").style.display = "block";
                            document.getElementById("blogPostStyleSheet").style.display = "block";
                        } else {
                            document.getElementById("blogStyleSheet").style.display = "none";
                            document.getElementById("blogPostStyleSheet").style.display = "none";
                        }
                    }
                </script>
            </body>
        <?php } else if ($URI_parts[1] and $URI_parts[1] == 'post') {
            if (file_exists("config.php")) { ?>
                <head>
                    <title><?php echo($blogName); ?> | New Post</title>
                    <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                    <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/setup.css" />
                </head>
                <body>
                    <a href="https://github.com/johnroper100/dropplets/tree/2.0"><img src="https://rawgit.com/johnroper100/dropplets/2.0/logo.svg" class="headerLogo" /></a>
                    <form method="post" action="post">
                        <input type="text" name="blogPostTitle" placeholder="Post Title:" required />
                        <textarea name="blogPostContent" placeholder="Post Content:" required></textarea>
                        <select name="blogPostStyleType" onchange="showStyleInput(this);">
                            <option value="default" selected>Use Default Stylesheet</option>
                            <option value="custom">Use Custom Stylesheet</option>
                        </select>
                        <input id="blogPostStyleSheet" type="url" name="blogPostStyleSheet" placeholder="Custom Stylesheet Link:" />
                        <input type="password" name="blogPassword" placeholder="Management Password:" required />
                        <input type="hidden" name="form" value="post" required />
                        <input class="btn" type="submit" value="Publish New Post" />
                    </form>
                    <script>
                    document.getElementById("blogPostStyleSheet").style.display = "none";
                    function showStyleInput(that) {
                        if (that.value == "custom") {
                            document.getElementById("blogPostStyleSheet").style.display = "block";
                        } else {
                            document.getElementById("blogPostStyleSheet").style.display = "none";
                        }
                    }
                </script>
                </body>
            <?php } else {
                header("Location: setup");
            }
        } else if ($URI_parts[1] and $URI_parts[1] == 'version') { ?>
            <head>
                <title><?php echo($blogName); ?> | New Post</title>
                <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/setup.css" />
            </head>
            <body>
                <a href="https://github.com/johnroper100/dropplets/tree/2.0"><img src="https://rawgit.com/johnroper100/dropplets/2.0/logo.svg" class="headerLogo" /></a>
                <h3>Dropplets v2.0 Beta - Licensed Under the GPL 3.0 License</h3>
            </body>
        <?php } else if ($URI_parts[1] and $URI_parts[1] == 'update') { ?>
            <head>
                <title><?php echo($blogName); ?> | Update</title>
                <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                <link type="text/css" rel="stylesheet" href="https://rawgit.com/johnroper100/dropplets/2.0/setup.css" />
            </head>
            <body>
                <a href="https://github.com/johnroper100/dropplets/tree/2.0"><img src="https://rawgit.com/johnroper100/dropplets/2.0/logo.svg" class="headerLogo" /></a>
                <form method="post" action="post">
                    <input type="password" name="blogPassword" placeholder="Management Password:" required />
                    <input type="hidden" name="form" value="update" required />
                    <input class="btn" type="submit" value="Update Dropplets" />
                </form>
            </body>
        <?php } else if ($URI_parts[1] and $URI_parts[1] == 'posts' and $URI_parts[2]) {
            // If the config exists, read it and display the blog.
            if (file_exists("config.php")) {
                include "posts/post$URI_parts[2].php";
                ?>
                <head>
                    <title><?php echo($blogName); ?> | <?php echo($postTitle); ?></title>
                    <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                    <link type="text/css" rel="stylesheet" href="<?php echo($postStyleSheet); ?>" />
                    <?php echo($headerInject); ?>
                </head>
                <body>
                    <div id="header">
                        <a id="siteTitleLink" href="/"><h1 id="siteTitle"><?php echo($blogName); ?></h1></a>
                    </div>
                    <h2 id="postTitle"><?php echo($postTitle); ?></h2>
                    <h3 id="postSubtitle"><?php echo($postDate); ?></h3>
                    <div id="postContent"><?php echo(Slimdown::render($postContent)); ?></div>
                    <div id="footer">
                        <p class="footerText"><?php echo($blogCopyright); ?></p>
                    </div>
                    <?php echo($footerInject); ?>
                </body>
            <?php } else {
                header("Location: setup");
            }
         } else {
            // If the config exists, read it and display the blog.
            if (file_exists("config.php")) { ?>
                <head>
                    <title><?php echo($blogName); ?> | Home</title>
                    <link type="text/css" rel="stylesheet" href="https://necolas.github.io/normalize.css/8.0.0/normalize.css" />
                    <link type="text/css" rel="stylesheet" href="<?php echo($blogStyleSheet); ?>" />
                    <?php echo($headerInject); ?>
                </head>
                <body>
                    <div id="header">
                        <h1 id="siteTitle"><?php echo($blogName); ?></h1>
                    </div>
                    <div class="posts">
                        <?php
                        $posts = array_reverse(glob('posts/*.{php}', GLOB_BRACE));
                        $index = count($posts)+1;
                        foreach($posts as $post) {
                            include $post;
                            $index--;
                            echo("<div class=\"post\"><a href=\"posts/$index\"><h2 id=\"postTitle\">$postTitle</h2></a><h3 id=\"postSubtitle\">$postDate</h3><div id=\"postContent\">".substr(Slimdown::render($postContent), 0, 250)."</div></div>");
                        }
                        ?>
                    </div>
                    <div id="footer">
                        <p class="footerText"><?php echo($blogCopyright); ?></p>
                    </div>
                    <?php echo($footerInject); ?>
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
    class Slimdown {
        public static $rules = array (
            '/(#+)(.*)/' => 'self::header',                           // headers
            '/\[([^\[]+)\]\(([^\)]+)\)/' => '<a href=\'\2\'>\1</a>',  // links
            '/\n\*(.*)/' => 'self::ul_list',                          // ul lists
            '/(\*\*|__)(.*?)\1/' => '<strong>\2</strong>',            // bold
            '/(\*|_)(.*?)\1/' => '<em>\2</em>',                       // emphasis
            '/\~\~(.*?)\~\~/' => '<del>\1</del>',                     // del
            '/\:\"(.*?)\"\:/' => '<q>\1</q>',                         // quote
            '/`(.*?)`/' => '<code>\1</code>',                         // inline code
            '/\n[0-9]+\.(.*)/' => 'self::ol_list',                    // ol lists
            '/\n(&gt;|\>)(.*)/' => 'self::blockquote ',               // blockquotes
            '/\n-{5,}/' => "\n<hr />",                                // horizontal rule
            '/\n([^\n]+)\n/' => 'self::para',                         // add paragraphs
            '/<\/ul>\s?<ul>/' => '',                                  // fix extra ul
            '/<\/ol>\s?<ol>/' => '',                                  // fix extra ol
            '/<\/blockquote><blockquote>/' => "\n"                    // fix extra blockquote
        );
        private static function para ($regs) {
            $line = $regs[1];
            $trimmed = trim ($line);
            if (preg_match ('/^<\/?(ul|ol|li|h|p|bl)/', $trimmed)) {
                return "\n" . $line . "\n";
            }
            return sprintf ("\n<p>%s</p>\n", $trimmed);
        }
        private static function ul_list ($regs) {
            $item = $regs[1];
            return sprintf ("\n<ul>\n\t<li>%s</li>\n</ul>", trim ($item));
        }
        private static function ol_list ($regs) {
            $item = $regs[1];
            return sprintf ("\n<ol>\n\t<li>%s</li>\n</ol>", trim ($item));
        }
        private static function blockquote ($regs) {
            $item = $regs[2];
            return sprintf ("\n<blockquote>%s</blockquote>", trim ($item));
        }
        private static function header ($regs) {
            list ($tmp, $chars, $header) = $regs;
            $level = strlen ($chars);
            return sprintf ('<h%d>%s</h%d>', $level, trim ($header), $level);
        }
        /**
         * Render some Markdown into HTML.
         */
        public static function render ($text) {
            $text = "\n" . $text . "\n";
            foreach (self::$rules as $regex => $replacement) {
                if (is_callable ( $replacement)) {
                    $text = preg_replace_callback ($regex, $replacement, $text);
                } else {
                    $text = preg_replace ($regex, $replacement, $text);
                }
            }
            return trim ($text);
        }
    }
?>
</html>
