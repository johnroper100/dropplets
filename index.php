<?php

require_once "./SleekDB/src/Store.php";
require_once "./AltoRouter/AltoRouter.php";
require_once "./parsedown/Parsedown.php";
require_once "./parsedown-extra/ParsedownExtra.php";

use SleekDB\Store;

if (file_exists("config.php")) {
    require_once 'config.php';
} else {
    $URI = parse_url($_SERVER['REQUEST_URI']);
    $siteConfig = [
        "name" => "",
        "info" => "",
        "footer" => "",
        "password" => "",
        "basePath" => "/" . explode('/', $URI['path'])[1],
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

/*$post = [
    "title" => "Test Blog Post",
    "date" => time(),
    "draft" => false,
    "content" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
];
$results = $blogStore->insert($post);*/

/*$blogStore->deleteStore();*/

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
        require __DIR__ . '/views/home.php';
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
        require __DIR__ . '/views/home.php';
    } else {
        header("Location: " . $router->generate('setup'));
    }
}, 'posts');

$router->map('GET|POST', '/setup', function () {
    global $siteConfig;
    global $router;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["blogName"]) && isset($_POST["blogPassword"])) {
            $password_hashed = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);

            $siteConfig['name'] = test_input($_POST["blogName"]);
            $siteConfig['password'] = $password_hashed;
            $config_content = '
            <?php
            $siteConfig = [
                "name" => "' . $siteConfig['name'] . '",
                "info" => "' . $siteConfig['info'] . '",
                "footer" => "' . $siteConfig['footer'] . '",
                "password" => "' . $siteConfig['password'] . '",
                "basePath" => "' . $siteConfig['basePath'] . '",
                "timezone" => "' . $siteConfig['timezone'] . '",
            ];
            ?>
            ';
            $config = fopen("config.php", 'w') or die("Unable to set up needed files! Please make sure index.php has write permissions and that the folder it is in has write permissions. This is usally 755.");
            fwrite($config, $config_content);
            fclose($config);
            header("Location: " . $router->generate('home'));
        } else {
            header("Location: " . $router->generate('setup'));
        }
    } else {
        $pageTitle = "Setup";
        require __DIR__ . '/views/setup.php';
    }
}, 'setup');

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
            require __DIR__ . '/views/post.php';
        }
    } else {
        header("Location: " . $router->generate('setup'));
    }
}, 'post');

$match = $router->match();

if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    echo ("404 Not Found");
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlentities($data, ENT_QUOTES);
    return $data;
}
