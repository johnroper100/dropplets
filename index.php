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

        $allPosts = $blogStore->findAll();
        $postCount = count($allPosts);
        usort($allPosts, function ($item1, $item2) {
            return $item2['date'] <=> $item1['date'];
        });
        $allPosts = array_slice($allPosts, $skip, $limit);
        $pageTitle = "Home";
        require __DIR__ . '/templates/' . $siteConfig['template'] . '/home.php';
    } else {
        header("Location: " . $router->generate('setup'));
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

        $allPosts = $blogStore->findAll();
        $postCount = count($allPosts);
        usort($allPosts, function ($item1, $item2) {
            return $item2['date'] <=> $item1['date'];
        });
        $allPosts = array_slice($allPosts, $skip, $limit);
        $pageTitle = "Posts";
        require __DIR__ . '/templates/' . $siteConfig['template'] . '/home.php';
    } else {
        header("Location: " . $router->generate('setup'));
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
        header("Location: " . $router->generate('setup'));
    }
}, 'post');

$router->map('GET|POST', '/setup', function () {
    global $siteConfig;
    global $router;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["blogName"]) && isset($_POST["blogPassword"])) {
            if (!file_exists("config.php")) {
                $password_hashed = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);
                $config_content = "<?php\n\$siteConfig = ['name'=>'" . test_input($_POST["blogName"]) . "',\n'info' => '" . test_input($_POST["blogInfo"]) . "',\n'footer' => '" . test_input($_POST["blogFooter"]) . "',\n'password' => '" . $password_hashed . "',\n'template' => '" . test_input($_POST["blogTemplate"]) . "',\n'basePath' => '" . test_input($_POST["blogBase"]) . "',\n'timezone' => '" . test_input($_POST["blogTimezone"]) . "',\n]\n?>";
                $config = fopen("config.php", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions. This is usally 755.");
                fwrite($config, $config_content);
                fclose($config);
                header("Location: " . $router->generate('home'));
            } else {
                if (password_verify($_POST["blogPassword"], $siteConfig['password'])) {
                    $config_content = "<?php\n\$siteConfig = ['name'=>'" . test_input($_POST["blogName"]) . "',\n'info' => '" . test_input($_POST["blogInfo"]) . "',\n'footer' => '" . test_input($_POST["blogFooter"]) . "',\n'password' => '" . $siteConfig['password'] . "',\n'template' => '" . test_input($_POST["blogTemplate"]) . "',\n'basePath' => '" . test_input($_POST["blogPath"]) . "',\n'timezone' => '" . test_input($_POST["blogTimezone"]) . "',\n]\n?>";
                    $config = fopen("config.php", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions. This is usally 755.");
                    fwrite($config, $config_content);
                    fclose($config);
                    header("Location: " . $router->generate('home'));
                } else {
                    header("Location: " . $router->generate('setup'));
                }
            }
        } else {
            header("Location: " . $router->generate('setup'));
        }
    } else {
        $pageTitle = "Setup";
        require __DIR__ . '/internal/setup.php';
    }
}, 'setup');

$router->map('GET|POST', '/write', function () {
    global $siteConfig;
    global $router;
    global $blogStore;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["blogPostTitle"]) && isset($_POST["blogPostContent"]) && isset($_POST["blogPostAuthor"]) && isset($_POST["blogPassword"])) {
            if (password_verify($_POST["blogPassword"], $siteConfig['password'])) {
                $post = [
                    "title" => test_input($_POST["blogPostTitle"]),
                    "date" => time(),
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
            header("Location: " . $router->generate('write'));
        }
    } else {
        $pageTitle = "Write";
        require __DIR__ . '/internal/write.php';
    }
}, 'write');

$match = $router->match();

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
