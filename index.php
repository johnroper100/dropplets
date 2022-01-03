<?php

require_once "./SleekDB/src/Store.php";
require_once "./AltoRouter/AltoRouter.php";
require_once "./parsedown/Parsedown.php";
require_once "./parsedown-extra/ParsedownExtra.php";
require_once "./internal/upload.php";

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
        "headerInject" => "",
        "password" => "",
        "template" => "liquid",
        "basePath" => $URI,
        "timezone" => "America/New_York",
        "I18N" => "us_EN",
    ];
}

require_once "./internal/i18n.php";

date_default_timezone_set($siteConfig['timezone']);

$Extra = new ParsedownExtra();

$router = new AltoRouter();

if ($siteConfig['basePath'] != null) {
    $router->setBasePath($siteConfig['basePath']);
}

$databaseDirectory = __DIR__ . "/siteDatabase";
$blogStore = new Store("blog", $databaseDirectory);
$imageStore = new Store("images", $databaseDirectory);

//$blogStore->deleteStore();

$router->map('GET', '/', function () {
    global $router;
    if (file_exists("config.php")) {
        global $siteConfig;
        global $blogStore;
        global $imageStore;
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
        global $imageStore;
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

$router->map('GET|POST', '/post/[i:id]', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        global $siteConfig;
        global $blogStore;
        global $imageStore;
        global $Extra;

        $post = $blogStore->findById($id);
        if ($post == null) {
            echo ("404 Not Found");
        } else {
            $passAttempt = "";
            if (isset($_REQUEST['password'])) {
                $passAttempt = $_REQUEST['password'];
            }
            if (empty($post["password"]) || $passAttempt === $post["password"]) {
                $pageTitle = $post['title'];
                require __DIR__ . '/templates/' . $siteConfig['template'] . '/post.php';
            } else {
                $pageTitle = "Private post";
                require __DIR__ . '/internal/private.php';
            }
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'post');

$router->map('GET', '/post/[i:id]/publish', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;
            global $imageStore;

            $post = $blogStore->findById($id);
            if ($post == null) {
                echo ("404 Not Found");
            } else {
                $blogStore->updateById($id, ["draft" => false]);
                header("Location: " . $router->generate('dashboard'));
            }
        } else {
            header("Location: " . $router->generate('login'));
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'publish');

$router->map('GET', '/post/[i:id]/hide', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;
            global $imageStore;

            $post = $blogStore->findById($id);
            if ($post == null) {
                echo ("404 Not Found");
            } else {
                $blogStore->updateById($id, ["draft" => true]);
                header("Location: " . $router->generate('dashboard'));
            }
        } else {
            header("Location: " . $router->generate('login'));
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'hide');

$router->map('GET|POST', '/post/[i:id]/edit', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;
            global $imageStore;

            $post = $blogStore->findById($id);
            if ($post == null) {
                echo ("404 Not Found");
            } else {
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if (isset($_POST["blogPostTitle"]) && isset($_POST["blogPostContent"]) && isset($_POST["blogPostAuthor"])) {
                        $post['title'] = test_input($_POST["blogPostTitle"]);
                        $post['author'] = test_input($_POST["blogPostAuthor"]);
                        $post['image'] = test_input($_POST["blogPostImageURL"]);
                        $post['password'] = test_input($_POST["blogPostPassword"]);
                        $post['content'] = test_input($_POST["blogPostContent"]);
                        $blogStore->update($post);
                        header("Location: " . $router->generate('dashboard'));
                    } else {
                        header("Location: " . $router->generate('editPost', ['id' => $id]));
                    }
                } else {
                    $pageTitle = "Edit Post";
                    require __DIR__ . '/internal/write.php';
                }
            }
        } else {
            header("Location: " . $router->generate('login'));
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'editPost');

$router->map('GET', '/post/[i:id]/delete', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;
            global $imageStore;

            $post = $blogStore->findById($id);
            if ($post == null) {
                echo ("404 Not Found");
            } else {
                $blogStore->deleteById($id);
                header("Location: " . $router->generate('dashboard'));
            }
        } else {
            header("Location: " . $router->generate('login'));
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'deletePost');

$router->map('GET|POST', '/settings', function () {
    global $siteConfig;
    global $router;
    if (isset($_SESSION['isAuthenticated']) || !file_exists("config.php")) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["blogName"]) && isset($_POST["blogTimezone"]) && isset($_POST["blogTemplate"]) && isset($_POST["blogI18N"])) {
                if (!file_exists("config.php")) {
                    $password_hashed = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);
                } else {
                    $password_hashed = $siteConfig['password'];
                }
                $config_content = "<?php\n\$siteConfig = [ \n"
                    . "'name'=>'" . test_input($_POST["blogName"]) . "',\n"
                    . "'info' => '" . test_input($_POST["blogInfo"]) . "',\n"
                    . "'footer' => '" . test_input($_POST["blogFooter"]) . "',\n"
                    . "'headerInject' => '" . base64_encode($_POST["blogHeaderInject"]) . "',\n"
                    . "'password' => '" . $password_hashed . "',\n"
                    . "'template' => '" . test_input($_POST["blogTemplate"]) . "',\n"
                    . "'basePath' => '" . test_input($_POST["blogBase"]) . "',\n"
                    . "'timezone' => '" . test_input($_POST["blogTimezone"]) . "',\n"
                    . "'I18N' => '" . test_input($_POST["blogI18N"]) . "',\n"
                    . "]\n?>";
                $config = fopen("config.php", 'w') or die("Unable to set up needed files! Please make sure index.php has write
permissions and that the folder it is in has write permissions. This is usally 755.");
                fwrite($config, $config_content);
                fclose($config);
                header("Location: " . $router->generate('home'));
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
    global $imageStore;

    if (isset($_SESSION['isAuthenticated'])) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["blogPostTitle"]) && isset($_POST["blogPostContent"]) && isset($_POST["blogPostAuthor"])) {
                $post = [
                    "title" => test_input($_POST["blogPostTitle"]),
                    "date" => time(),
                    "draft" => true,
                    "author" => test_input($_POST["blogPostAuthor"]),
                    "content" => test_input($_POST["blogPostContent"]),
                    "password" => test_input($_POST["blogPostPassword"])
                ];

                if ($_FILES["imageUpload"] != "") {
                    $uploadedFile = $_FILES["imageUpload"];
                    $verified = verifyImage($uploadedFile);

                    if ($verified != "ERR") {
                        $record = [
                            "base64" => $verified[0],
                            "type" => $verified[1]
                        ];
                        $imageWriteResult = $imageStore->insert($record);
                        $post["image"] = $imageWriteResult["_id"];
                    } else {
                        echo "Unable to upload image";
                    }
                } elseif (isset($_POST["blogPostImageURL"])) {
                    $post["image"] = test_input($_POST["blogPostImageURL"]);
                }
                $results = $blogStore->insert($post);
                header("Location: " . $router->generate('dashboard'));
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
    global $blogStore;
    global $imageStore;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            $allPosts = $blogStore->findAll();
            $postCount = $blogStore->count();
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
