<?php
if (file_exists("config.php")) {
    include 'config.php';
} else {
    $blogName = null;
    $blogAuthor = null;
    $blogCopyright = null;
    $blogPassword = null;
}
// Create the required .htaccess if it dosen't already exist.
if (!file_exists(".htaccess")) {
    $htaccess = fopen(".htaccess", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions.");
    $htaccess_content = "<IfModule mod_rewrite.c>\n\tRewriteEngine On\n\tRewriteCond %{REQUEST_FILENAME} !-f\n\tRewriteCond %{REQUEST_FILENAME} !-d\n\tRewriteCond %{REQUEST_FILENAME} !-l\n\tRewriteRule ^(.*)$ index.php?/$1 [L,QSA]\n</IfModule>";
    fwrite($htaccess, $htaccess_content);
    fclose($htaccess);
}
if (!file_exists("posts")) {
    mkdir("posts");
}
// Get the url parameters.
$URI = parse_url($_SERVER['REQUEST_URI']);
$URI_parts = explode('/', $URI['path']);

$URI_parts = array_reverse(explode('/', rtrim($URI['path'], '/')));
// If a form is submitted, process it. Otherwise, show the main web page.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Setup form submitted, create config.php.
    if (test_input($_POST["form"]) == "setup") {
        // Check that the form items are there.
        if (isset($_POST["blogName"]) and isset($_POST["blogAuthor"]) and isset($_POST["blogCopyright"]) and isset($_POST["blogPassword"]) and isset($_POST["blogStyleType"])) {
            // Get the stylesheet link (placed here to be used in both the if config.php and if not)
            if (test_input($_POST["blogStyleType"]) == "default") {
                $blogStyleSheet = "https://raw.githack.com/johnroper100/dropplets/2.0/main.css";
                $blogPostStyleSheet = "https://raw.githack.com/johnroper100/dropplets/2.0/main.css";
            } else if (test_input($_POST["blogStyleType"]) == "zen") {
                $blogStyleSheet = "https://raw.githack.com/johnroper100/dropplets/2.0/zenStyle.css";
                $blogPostStyleSheet = "https://raw.githack.com/johnroper100/dropplets/2.0/zenStyle.css";
            } else {
                $blogStyleSheet = test_input($_POST["blogStyleSheet"]);
                $blogPostStyleSheet = test_input($_POST["blogPostStyleSheet"]);
            }
            // If the config does not exist, create it. If it does, update it.
            if (!file_exists("config.php")) {
                $password_hash = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);

                $config_content = "<?php\n\$blogName='" . test_input($_POST["blogName"]) . "';\n\$blogAuthor='" . test_input($_POST["blogAuthor"]) . "';\n\$blogCopyright='" . test_input($_POST["blogCopyright"]) . "';\n\$blogPassword='" . $password_hash . "';\n\$blogStyleType='" . test_input($_POST["blogStyleType"]) . "';\n\$blogStyleSheet='" . $blogStyleSheet . "';\n\$blogPostStyleSheet='" . $blogPostStyleSheet . "';\n\$headerInject='';\n\$footerInject='';\n?>";
            } else {
                if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                    $config_content =
                        "<?php\n\$blogName='" . test_input($_POST["blogName"]) . "';\n\$blogAuthor='" . test_input($_POST["blogAuthor"]) . "';\n\$blogCopyright='" . test_input($_POST["blogCopyright"]) . "';\n\$blogPassword='" . $blogPassword . "';\n\$blogStyleType='" . test_input($_POST["blogStyleType"]) . "';\n\$blogStyleSheet='" . $blogStyleSheet . "';\n\$blogPostStyleSheet='" . $blogPostStyleSheet . "';\n\$headerInject='" . $headerInject . "';\n\$footerInject='" . $footerInject . "';\n?>";
                } else {
                    echo ("Management password not correct!");
                    exit;
                }
            }
        }
        $config = fopen("config.php", 'w') or die("Unable to set up needed files! Please make sure index.php has write
permissions and that the folder it is in has write permissions.");
        fwrite($config, $config_content);
        fclose($config);
        header("Location: " . dirname($_SERVER['REQUEST_URI']));
    } else if (test_input($_POST["form"]) == "post") {
        if (file_exists("config.php")) {
            if (
                isset($_POST["blogPostTitle"]) and isset($_POST["blogPostContent"]) and isset($_POST["blogPassword"]) and
                isset($_POST["blogPostStyleType"])
            ) {
                if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                    if (test_input($_POST["blogPostStyleType"]) == "default") {
                        $postStyleSheet = $blogPostStyleSheet;
                    } else {
                        $postStyleSheet = test_input($_POST["blogPostStyleSheet"]);
                    }
                    $post_content =
                        "<?php\n\$postTitle='" . test_input($_POST["blogPostTitle"]) . "';\n\$postContent='" . $_POST["blogPostContent"] . "';\n\$postDate='" . date("F jS, Y") . "';\n\$postStyleSheet='" . $postStyleSheet . "';\n?>";

                    if (!file_exists("posts/" . date("Y"))) {
                        mkdir("posts/" . date("Y"));
                    }
                    if (!file_exists("posts/" . date("Y") . "/" . date("m"))) {
                        mkdir("posts/" . date("Y") . "/" . date("m"));
                    }
                    if (!file_exists("posts/" . date("Y") . "/" . date("m") . "/" . date("d"))) {
                        mkdir("posts/" . date("Y") . "/" . date("m") . "/" . date("d"));
                    }
                    if (!file_exists("posts/" . date("Y") . "/" . date("m") . "/" . date("d") . "/" . urlencode(test_input($_POST["blogPostTitle"])) . ".php")) {
                        $result = urlencode(test_input($_POST["blogPostTitle"])) . ".php";
                    } else {
                        header("Location: /post");
                    }
                    $post = fopen("posts/" . date("Y") . "/" . date("m") . "/" . date("d") . "/" . $result, 'w') or die("Unable to set up needed files!
Please make sure index.php has write
permissions and that the folder it is in has write permissions.");
                    fwrite($post, $post_content);
                    fclose($post);
                    header("Location: " . dirname($_SERVER['REQUEST_URI']));
                } else {
                    echo ("Management password not correct!");
                    exit;
                }
            }
        } else {
            header("Location: setup");
        }
    } else if (test_input($_POST["form"]) == "postUpload") {
        if (file_exists("config.php")) {
            if (
                isset($_POST["blogPostTitle"]) and isset($_POST["blogPostFile"]) and isset($_POST["blogPassword"]) and
                isset($_POST["blogPostStyleType"])
            ) {
                if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                    if (test_input($_POST["blogPostStyleType"]) == "default") {
                        $postStyleSheet = $blogPostStyleSheet;
                    } else {
                        $postStyleSheet = test_input($_POST["blogPostStyleSheet"]);
                    }
                    $post_content =
                        "<?php\n\$postTitle='" . test_input($_POST["blogPostTitle"]) . "';\n\$postContent='" . $_POST["blogPostFile"] . "';\n\$postDate='" . date("F jS, Y") . "';\n\$postStyleSheet='" . $postStyleSheet . "';\n?>";

                    if (!file_exists("posts/" . date("Y"))) {
                        mkdir("posts/" . date("Y"));
                    }
                    if (!file_exists("posts/" . date("Y") . "/" . date("m"))) {
                        mkdir("posts/" . date("Y") . "/" . date("m"));
                    }
                    if (!file_exists("posts/" . date("Y") . "/" . date("m") . "/" . date("d"))) {
                        mkdir("posts/" . date("Y") . "/" . date("m") . "/" . date("d"));
                    }
                    if (!file_exists("posts/" . date("Y") . "/" . date("d") . "/" . date("m") . "/" . urlencode(test_input($_POST["blogPostTitle"])) . ".php")) {
                        $result = urlencode(test_input($_POST["blogPostTitle"])) . ".php";
                    } else {
                        header("Location: /post");
                    }
                    $post = fopen("posts/" . date("Y") . "/" . date("m") . "/" . date("d") . "/" . $result, 'w') or die("Unable to set up needed files!
Please make sure index.php has write
permissions and that the folder it is in has write permissions.");
                    fwrite($post, $post_content);
                    fclose($post);
                    header("Location: " . dirname($_SERVER['REQUEST_URI']));
                } else {
                    echo ("Management password not correct!");
                    exit;
                }
            }
        } else {
            header("Location: setup");
        }
    } else if (test_input($_POST["form"]) == "update") {
        if (file_exists("config.php")) {
            if (isset($_POST["blogPassword"])) {
                if (password_verify(test_input($_POST["blogPassword"]), $blogPassword)) {
                    file_put_contents("index.php", fopen("https://raw.githack.com/johnroper100/dropplets/2.0/index.php", 'r'));
                    header("Location: " . dirname($_SERVER['REQUEST_URI']));
                } else {
                    echo ("Management password not correct!");
                    exit;
                }
            }
        } else {
            header("Location: setup");
        }
    } else {
        echo ("The form could not be submitted. Please try again later.");
    }
} else {
    // If the url is setup, check for config and then show the setup page.
    if (count($URI_parts) >= 1 and $URI_parts[0] and $URI_parts[0] == 'setup') {
        if (!isset($blogStyleType)) {
            $blogStyleType = 'default';
        }
        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Dropplets | Setup</title>
            <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/reset.css" />
            <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/setup.css" />
        </head>

        <body>
            <main>
                <div class="setupHeader">
                    <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span> </a>
                </div>
                <h1>Let's create your blog</h1>
                <form method="post" action="setup">
                    <fieldset>
                        <legend>First, some details:</legend>
                        <input type="text" name="blogName" placeholder="Give a name to your blog" required value="<?php echo ($blogName); ?>" />
                        <input type="text" name="blogAuthor" placeholder="Who's the author ?" required value="<?php echo ($blogAuthor); ?>" />
                        <input type="text" name="blogCopyright" placeholder="Write a copyright message" required value="<?php echo ($blogCopyright); ?>" />
                    </fieldset>

                    <fieldset>
                        <legend>Second step, choose your stylesheet:</legend>
                        <select name="blogStyleType" onchange="showStyleInput(this);">
                            <option value="default" <?php if ($blogStyleType == 'default') { ?>selected<?php } ?>>Use the
                                default one</option>
                            <!--<option value="zen" <?php if ($blogStyleType == 'zen') { ?>selected<?php } ?>>Use the zen one</option>-->
                            <option value="custom" <?php if ($blogStyleType == 'custom') { ?>selected<?php } ?>>I want my own
                                style!</option>
                        </select>
                        <input id="blogStyleSheet" type="url" name="blogStyleSheet" placeholder="URL to your homepage stylesheet" value="<?php if ($blogStyleType == 'custom') {
                                                                                                                                                echo ($blogStyleSheet);
                                                                                                                                            } ?>" />
                        <input id="blogPostStyleSheet" type="url" name="blogPostStyleSheet" placeholder="URL to your post stylesheet" value="<?php if ($blogStyleType == 'custom') {
                                                                                                                                                    echo ($blogPostStyleSheet);
                                                                                                                                                } ?>" />
                    </fieldset>
                    <fieldset>
                        <legend>Last but not least, the password:</legend>
                        <input type="password" name="blogPassword" placeholder="Choose a good password" required />
                    </fieldset>
                    <input type="hidden" name="form" value="setup" required />
                    <input class="btn" type="submit" value="Create your blog" />
                </form>
            </main>
            <script>
                <?php if ($blogStyleType == 'default' or $blogStyleType == 'zen') { ?>
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

        </html>
    <?php } else if (count($URI_parts) >= 1 and $URI_parts[0] and $URI_parts[0] == 'post') {
    if (file_exists("config.php")) { ?>
            <!DOCTYPE html>
            <html>

            <head>
                <title><?php echo ($blogName); ?> | New Post</title>
                <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/reset.css" />
                <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/setup.css" />
            </head>

            <body>
                <main>
                    <div class="setupHeader">
                        <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span> </a>
                    </div>
                    <h1>Time to write your prose</h1>
                    <form method="post" action="post">
                        <fieldset>
                            <input type="text" name="blogPostTitle" class="blogPostTitle" placeholder="The post title" required />
                            <textarea name="blogPostContent" placeholder="Write your post here, you can use Markdown" required></textarea>
                        </fieldset>
                        <fieldset>
                            <p class="details">You can personnalise the style of your post here</p>
                            <select name="blogPostStyleType" onchange="showStyleInput(this);">
                                <option value="default" selected>Use Default Stylesheet</option>
                                <option value="custom">Use a custom stylesheet</option>
                            </select>
                            <input id="blogPostStyleSheet" type="url" name="blogPostStyleSheet" placeholder="Custom stylesheet link" />
                        </fieldset>
                        <fieldset>
                            <input type="password" name="blogPassword" placeholder="Management Password:" required />
                        </fieldset>
                        <input type="hidden" name="form" value="post" required />
                        <input class="btn" type="submit" value="Publish New Post" />
                    </form>
                </main>
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

            </html>
        <?php }
} else if (count($URI_parts) >= 1 and $URI_parts[0] and $URI_parts[0] == 'postUpload') {
    if (file_exists("config.php")) { ?>
            <!DOCTYPE html>
            <html>

            <head>
                <title><?php echo ($blogName); ?> | New Post Upload</title>
                <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/reset.css" />
                <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/setup.css" />
            </head>

            <body>
                <main>
                    <div class="setupHeader">
                        <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span> </a>
                    </div>
                    <h1>Time to write your prose</h1>
                    <form method="post" action="post">
                        <fieldset>
                            <input type="text" name="blogPostTitle" class="blogPostTitle" placeholder="The post title" required />
                            <input type="file" name="blogPostFile" class="blogPostFile" placeholder="The post file" required />
                        </fieldset>
                        <fieldset>
                            <p class="details">You can personnalise the style of your post here</p>
                            <select name="blogPostStyleType" onchange="showStyleInput(this);">
                                <option value="default" selected>Use Default Stylesheet</option>
                                <option value="custom">Use a custom stylesheet</option>
                            </select>
                            <input id="blogPostStyleSheet" type="url" name="blogPostStyleSheet" placeholder="Custom stylesheet link" />
                        </fieldset>
                        <fieldset>
                            <input type="password" name="blogPassword" placeholder="Management Password:" required />
                        </fieldset>
                        <input type="hidden" name="form" value="postUpload" required />
                        <input class="btn" type="submit" value="Publish New Post" />
                    </form>
                </main>
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

            </html>
        <?php } else {
        header("Location: setup");
    }
} else if (count($URI_parts) >= 1 and $URI_parts[0] and $URI_parts[0] == 'version') { ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title><?php echo ($blogName); ?> | Dropplets Version</title>
            <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/reset.css" />
            <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/setup.css" />
        </head>

        <body>
            <main>
                <div class="setupHeader">
                    <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span> </a>
                </div>
                <h3>Dropplets v2.0 Beta - Licensed Under the GPL 3.0 License</h3>
            </main>
        </body>

        </html>
    <?php } else if ($URI_parts[0] and $URI_parts[0] == 'update') { ?>
        <!DOCTYPE html>
        <html>

        <head>
            <title><?php echo ($blogName); ?> | Update</title>
            <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/reset.css" />
            <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/setup.css" />
        </head>

        <body>
            <main>
                <div class="setupHeader">
                    <a href="https://github.com/johnroper100/dropplets"><span class="headerLogo"></span><span class="droppletsName">Dropplets</span> </a>
                </div>
                <h1>Update</h1>
                <form method="post" action="post">
                    <fieldset>
                        <legend>Type your password to update your blog</legend>
                        <input type="password" name="blogPassword" placeholder="Management password" required />
                    </fieldset>
                    <input type="hidden" name="form" value="update" required />
                    <input class="btn" type="submit" value="Update Dropplets" />
                </form>
            </main>
        </body>

        </html>
    <?php } else if (count($URI_parts) >= 6 and $URI_parts[4] == 'posts' and $URI_parts[0] and $URI_parts[1] and $URI_parts[2] and $URI_parts[3]) {
    // If the config exists, read it and display the blog.
    if (file_exists("config.php")) {
        include "posts/$URI_parts[3]/$URI_parts[2]/$URI_parts[1]/$URI_parts[0].php";
        ?>
            <!DOCTYPE html>
            <html>

            <head>
                <title><?php echo ($blogName); ?> | <?php echo ($postTitle); ?></title>
                <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/reset.css" />
                <link type="text/css" rel="stylesheet" href="<?php echo ($postStyleSheet); ?>" />
                <?php echo ($headerInject); ?>
            </head>

            <body>
                <main id="postPage">
                    <header>
                        <a id="siteTitleLink" href="/">➜</a>
                    </header>
                    <div id="postTitleDate">
                        <h1 id="postTitle"><?php echo ($postTitle); ?></h1>
                        <span id="postSubtitle"><?php echo ($postDate); ?></span>
                    </div>
                    <div id="postContent"><?php echo (Slimdown::render($postContent)); ?></div>
                    <div id="footer">
                        <p class="footerText"><?php echo ($blogCopyright); ?></p>
                    </div>
                    <?php echo ($footerInject); ?>
                </main>
            </body>

            </html>
        <?php } else {
        header("Location: setup");
    }
} else {
    // If the config exists, read it and display the blog.
    if (file_exists("config.php")) { ?>
            <!DOCTYPE html>
            <html>

            <head>
                <title><?php echo ($blogName); ?> | Home</title>
                <link type="text/css" rel="stylesheet" href="https://raw.githack.com/johnroper100/dropplets/2.0/reset.css" />
                <link type="text/css" rel="stylesheet" href="<?php echo ($blogStyleSheet); ?>" />
                <?php echo ($headerInject); ?>
            </head>

            <body>
                <main id="index">
                    <header>
                        <h1 id="siteTitle"><?php echo ($blogName); ?></h1>
                    </header>
                    <div class="posts">
                        <?php
                        $it = new RecursiveDirectoryIterator("posts");
                        $display = array('php');
                        foreach (new RecursiveIteratorIterator($it) as $file) {
                            if (in_array(strtolower(array_pop(explode('.', $file))), $display)) {
                                include $file;
                                $paths = explode('/', $file);
                                echo ("<div class=\"post\"><h2 id=\"postTitle\"><a href=\"posts/$paths[1]/$paths[2]/$paths[3]/" . str_replace('.php', '', $paths[4]) . "\">$postTitle</a></h2><span id=\"postSubtitle\">$postDate</span><div id=\"postContent\">" . substr(Slimdown::render($postContent), 0, 250) . "</div></div>");
                            }
                        }
                        ?>
                    </div>
                    <div id="footer">
                        <p class="footerText"><?php echo ($blogCopyright); ?></p>
                    </div>
                    <?php echo ($footerInject); ?>
                </main>
            </body>

            </html>
        <?php } else {
        header("Location: setup");
    }
}
}
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
class Slimdown
{
    public static $rules = array(
        '/(#+)(.*)/' => 'self::header',                           // headers
        '/\[([^\[]+)\]\(([^\)]+)\)/' => '<a href=\'\2\'>\1</a>',  // links
        '/(?:!\[(.*?)\]\((.*?)\))/' => '<img src=\'\2\'>\1</img>',  // links
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
    private static function para($regs)
    {
        $line = $regs[1];
        $trimmed = trim($line);
        if (preg_match('/^<\/?(ul|ol|li|h|p|bl)/', $trimmed)) {
            return "\n" . $line . "\n";
        }
        return sprintf("\n<p>%s</p>\n", $trimmed);
    }
    private static function ul_list($regs)
    {
        $item = $regs[1];
        return sprintf("\n<ul>\n\t<li>%s</li>\n</ul>", trim($item));
    }
    private static function ol_list($regs)
    {
        $item = $regs[1];
        return sprintf("\n<ol>\n\t<li>%s</li>\n</ol>", trim($item));
    }
    private static function blockquote($regs)
    {
        $item = $regs[2];
        return sprintf("\n<blockquote>%s</blockquote>", trim($item));
    }
    private static function header($regs)
    {
        list($tmp, $chars, $header) = $regs;
        $level = strlen($chars);
        return sprintf('<h%d>%s</h%d>', $level, trim($header), $level);
    }
    /**
     * Render some Markdown into HTML.
     */
    public static function render($text)
    {
        $text = "\n" . $text . "\n";
        foreach (self::$rules as $regex => $replacement) {
            if (is_callable($replacement)) {
                $text = preg_replace_callback($regex, $replacement, $text);
            } else {
                $text = preg_replace($regex, $replacement, $text);
            }
        }
        return trim($text);
    }
}
?>