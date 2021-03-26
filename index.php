<?php

require_once "./SleekDB/src/Store.php";
require_once "./AltoRouter/AltoRouter.php";
require_once "./parsedown/Parsedown.php";
require_once "./parsedown-extra/ParsedownExtra.php";

use SleekDB\Store;

$siteConfig = [
    "name" => "My Blog",
    "footer" => "Copyright 2021, My Company",
    "basePath" => "/dropplets",
    "timezone" => "America/New_York",
];

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
    global $siteConfig;
    global $blogStore;
    global $Extra;
    global $router;
    $allPosts = $blogStore->findAll();
    usort($allPosts, function ($item1, $item2) {
        return $item2['date'] <=> $item1['date'];
    });
    $pageTitle = "Home";
    require __DIR__ . '/views/home.php';
});

$router->map('GET', '/setup', function () {
    global $siteConfig;
    global $router;
    $pageTitle = "Setup";
    require __DIR__ . '/views/setup.php';
});

$router->map('GET', '/posts/[i:id]', function ($id) {
    global $siteConfig;
    global $blogStore;
    global $Extra;
    global $router;
    $post = $blogStore->findById($id);
    if ($post == null) {
        echo ("404 Not Found");
    } else {
        $pageTitle = $post['title'];
        require __DIR__ . '/views/post.php';
    }
}, 'post');

$match = $router->match();

if (is_array($match) && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    echo ("404 Not Found");
}
