<?php

require_once "./SleekDB/src/Store.php";
require_once "./AltoRouter/AltoRouter.php";
require_once "./parsedown/Parsedown.php";
require_once "./parsedown-extra/ParsedownExtra.php";

use SleekDB\Store;

if (file_exists("config.php")) {
    require 'config.php';
} else {
    $URI = str_replace("index.php", "", $_SERVER['PHP_SELF']);
    if ($URI == "/") {
        $URI = null;
    }
    $siteConfig = [
        "name" => "",
        "info" => "",
        "footer" => "",
        "password" => "",
        "template" => "liquid",
        "basePath" => $URI,
        "timezone" => "America/New_York",
    ];
}

date_default_timezone_set($siteConfig['timezone']);

$Extra = new ParsedownExtra();

$router = new AltoRouter();

if ($siteConfig['basePath'] != null) {
    $router->setBasePath($siteConfig['basePath']);
}

$databaseDirectory = __DIR__ . "/siteDatabase";
$blogStore = new Store("blog", $databaseDirectory);

//$blogStore->deleteStore();

$router->map('GET', '/', function () {
    global $router;
    if (file_exists("config.php")) {
        global $siteConfig;
        global $blogStore;
        global $Extra;
        $page = 1;
        $limit = 5;
        $skip = ($page - 1) * $limit;

        $allPosts = $blogStore->findBy(["draft", "=", false], ["date" => "desc"], $limit, $skip);
        $postCount = count($blogStore->findBy(["draft", "=", false], ["date" => "desc"]));
        $pageTitle = "Home";
        require __DIR__ . '/templates/' . $siteConfig['template'] . '/home.php';
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'home');

$router->map('GET', '/[i:page]', function ($page) {
    global $router;
    if (file_exists("config.php")) {
        global $siteConfig;
        global $blogStore;
        global $Extra;

        $limit = 5;
        $skip = ($page - 1) * $limit;

        $allPosts = $blogStore->findBy(["draft", "=", false], ["date" => "desc"], $limit, $skip);
        $postCount = count($blogStore->findBy(["draft", "=", false], ["date" => "desc"]));
        $pageTitle = "Posts";
        require __DIR__ . '/templates/' . $siteConfig['template'] . '/home.php';
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'posts');

$router->map('GET', '/post/[i:id]', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        global $siteConfig;
        global $blogStore;
        global $Extra;

        $post = $blogStore->findById($id);
        if ($post == null) {
            echo ("404 Not Found");
        } else {
            $pageTitle = $post['title'];
            require __DIR__ . '/templates/' . $siteConfig['template'] . '/post.php';
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'post');

$router->map('GET|POST', '/settings', function () {
    global $siteConfig;
    global $router;
    if (isset($_SESSION['isAuthenticated']) || !file_exists("config.php")) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["blogName"]) && isset($_POST["blogTimezone"]) && isset($_POST["blogTemplate"])) {
                if (!file_exists("config.php")) {
                    $password_hashed = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);
                    $config_content = "<?php\n\$siteConfig = ['name'=>'" . test_input($_POST["blogName"]) . "',\n'info' => '" . test_input($_POST["blogInfo"]) . "',\n'footer' => '" . test_input($_POST["blogFooter"]) . "',\n'password' => '" . $password_hashed . "',\n'template' => '" . test_input($_POST["blogTemplate"]) . "',\n'basePath' => '" . test_input($_POST["blogBase"]) . "',\n'timezone' => '" . test_input($_POST["blogTimezone"]) . "',\n]\n?>";
                    $config = fopen("config.php", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions. This is usally 755.");
                    fwrite($config, $config_content);
                    fclose($config);
                    header("Location: " . $router->generate('home'));
                } else {
                    $config_content = "<?php\n\$siteConfig = ['name'=>'" . test_input($_POST["blogName"]) . "',\n'info' => '" . test_input($_POST["blogInfo"]) . "',\n'footer' => '" . test_input($_POST["blogFooter"]) . "',\n'password' => '" . $siteConfig['password'] . "',\n'template' => '" . test_input($_POST["blogTemplate"]) . "',\n'basePath' => '" . test_input($_POST["blogBase"]) . "',\n'timezone' => '" . test_input($_POST["blogTimezone"]) . "',\n]\n?>";
                    $config = fopen("config.php", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions. This is usally 755.");
                    fwrite($config, $config_content);
                    fclose($config);
                    header("Location: " . $router->generate('home'));
                }
            } else {
                header("Location: " . $router->generate('settings'));
            }
        } else {
            $pageTitle = "Settings";
            require __DIR__ . '/internal/settings.php';
        }
    } else {
        header("Location: " . $router->generate('login'));
    }
}, 'settings');

$router->map('GET|POST', '/write', function () {
    global $siteConfig;
    global $router;
    global $blogStore;
    if (isset($_SESSION['isAuthenticated'])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["blogPostTitle"]) && isset($_POST["blogPostContent"]) && isset($_POST["blogPostAuthor"])) {
                $post = [
                    "title" => test_input($_POST["blogPostTitle"]),
                    "date" => time(),
                    "draft" => true,
                    "author" => test_input($_POST["blogPostAuthor"]),
                    "image" => test_input($_POST["blogPostImage"]),
                    "content" => test_input($_POST["blogPostContent"]),
                ];
                $results = $blogStore->insert($post);
                header("Location: " . $router->generate('home'));
            } else {
                header("Location: " . $router->generate('write'));
            }
        } else {
            $pageTitle = "Write";
            require __DIR__ . '/internal/write.php';
        }
    } else {
        header("Location: " . $router->generate('login'));
    }
}, 'write');

$router->map('GET', '/logout', function () {
    global $router;
    if (file_exists("config.php")) {
        session_destroy();
        header("Location: " . $router->generate('home'));
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'logout');

$router->map('GET|POST', '/login', function () {
    global $router;
    global $siteConfig;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            header("Location: " . $router->generate('dashboard'));
        } else {
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                if (isset($_POST["blogPassword"])) {
                    if (password_verify($_POST["blogPassword"], $siteConfig['password'])) {
                        $_SESSION['isAuthenticated'] = true;
                        header("Location: " . $router->generate('dashboard'));
                    }
                }
                header("Location: " . $router->generate('login'));
            } else {
                $pageTitle = "Log In";
                require __DIR__ . '/internal/login.php';
            }
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'login');

$router->map('GET', '/dashboard', function () {
    global $router;
    global $siteConfig;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            $pageTitle = "Dashboard";
            require __DIR__ . '/internal/dashboard.php';
        } else {
            header("Location: " . $router->generate('login'));
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'dashboard');

$match = $router->match();

session_start();

if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    echo ("404 - Page Not Found");
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlentities($data, ENT_QUOTES);
    return $data;
}
