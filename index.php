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
        "domain" => "",
        "OGImage" => "",
        "footer" => "",
        "headerInject" => "",
        "password" => "",
        "template" => "liquid-new",
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

$dbconfiguration = [
    "timeout" => 60
];

$databaseDirectory = __DIR__ . "/siteDatabase";
$blogStore = new Store("blog", $databaseDirectory, $dbconfiguration);
$imageStore = new Store("images", $databaseDirectory, $dbconfiguration);

// Get Site Homepage - Config file must exist, User does not need to be authenticated
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

// Get Other Site Pages - Config file must exist, User does not need to be authenticated
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

// Get Specific Post - Config file must exist, User does not need to be authenticated
$router->map('GET|POST', '/post/[i:id]', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        global $siteConfig;
        global $blogStore;
        global $Extra;

        $post = $blogStore->findById($id);
        if ($post == null) {
            // Build out nice 404 page and header to it
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

// Publish a draft post - Config file must exist, User needs to be authenticated
$router->map('GET', '/post/[i:id]/publish', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;

            $post = $blogStore->findById($id);
            if ($post == null) {
                // Build out nice 404 page and header to it
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

// Unpublish a post - Config file must exist, User needs to be authenticated
$router->map('GET', '/post/[i:id]/hide', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;

            $post = $blogStore->findById($id);
            if ($post == null) {
                // Build out nice 404 page and header to it
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

// Need to do a check to see if the URL of blogPostImageURL was changed or not, don't reupload if its the same
// Edit a post - Config file must exist, User needs to be authenticated
$router->map('GET|POST', '/post/[i:id]/edit', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;
            global $imageStore;

            $post = $blogStore->findById($id);
            if ($post == null) {
                // Build out nice 404 page and header to it
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

// Needs to delete blogStore record, linked imageStore record, and delete the imageStore record's image path
// Delete a post - Config file must exist, User needs to be authenticated
$router->map('GET', '/post/[i:id]/delete', function ($id) {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;
            global $imageStore;

            $post = $blogStore->findById($id);
            if ($post == null) {
                // Build out nice 404 page and header to it
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

// Setup Blog - Config doesn't exist and needs to be created, User cannot be authenticated
// OR
// Configure Blog Settings - Config file must exist, User needs to be authenticated
$router->map('GET|POST', '/settings', function () {
    global $router;
    if (isset($_SESSION['isAuthenticated']) || !file_exists("config.php")) {
        global $siteConfig;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Should always correspond to required front-end values in ./internal/settings.php
            if (isset($_POST["blogName"]) && isset($_POST["blogDomain"]) && isset($_POST["blogTemplate"]) && isset($_POST["blogTimezone"]) && isset($_POST["blogI18N"])) {
                if (!file_exists("config.php")) {
                    $password_hashed = password_hash(test_input($_POST["blogPassword"]), PASSWORD_BCRYPT);
                } else {
                    $password_hashed = $siteConfig['password'];
                }
                $config_content = "<?php\n\$siteConfig = [ \n"
                    . "'name'=>'" . test_input($_POST["blogName"]) . "',\n"
                    . "'info' => '" . test_input($_POST["blogInfo"]) . "',\n"
                    . "'domain' => '" . test_input($_POST["blogDomain"]) . "',\n"
                    . "'OGImage' => '" . test_input($_POST["blogOGImage"]) . "',\n"
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
                header("Location: " . $router->generate('dashboard'));
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

// Write a post - Config file must exist, User needs to be authenticated
$router->map('GET|POST', '/write', function () {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;
            global $imageStore;
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
                    $verified = '';
                    // Uploaded image file always takes precedence over specified URL
                    if (is_uploaded_file($_FILES["imageUpload"])) {
                        $uploadedFile = $_FILES["imageUpload"];
                        $verified = verifyImage($uploadedFile, $siteConfig['domain']);
                    // Image specified via URL will be downloaded and stored to server
                    } elseif (isset($_POST["blogPostImageURL"])) {
                        $verified = downloadImage(test_input($_POST["blogPostImageURL"]), $siteConfig['domain']);
                    }
                    if ($verified != "ERR") {
                        $record = [
                            "url" => $verified[0],
                            "path" => $verified[1]
                        ];
                        $imageWriteResult = $imageStore->insert($record);
                        $post["image"] = $imageWriteResult["_id"];
                        $results = $blogStore->insert($post);
                        header("Location: " . $router->generate('dashboard'));
                    } else {
                        echo "!!! Error uploading or downloading image !!!";
                    }
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
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'write');

// Logout - Config file must exist, User needs to be authenticated
$router->map('GET', '/logout', function () {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            session_destroy();
            header("Location: " . $router->generate('home'));
        } else {
            header("Location: " . $router->generate('login'));
        }
    } else {
        header("Location: " . $router->generate('settings'));
    }
}, 'logout');

// Login - Config file must exist, User is authenticating so N/A
$router->map('GET|POST', '/login', function () {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
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

// Go to Dashboard - Config file must exist, User needs to be authenticated
$router->map('GET', '/dashboard', function () {
    global $router;
    if (file_exists("config.php")) {
        if (isset($_SESSION['isAuthenticated'])) {
            global $siteConfig;
            global $blogStore;
            $draftPosts = $blogStore->findBy(["draft", "=", true], ["date" => "desc"]);
            $draftPostCount = count($blogStore->findBy(["draft", "=", true], ["date" => "desc"]));
            $publishedPosts = $blogStore->findBy(["draft", "=", false], ["date" => "desc"]);
            $publishedPostCount = count($blogStore->findBy(["draft", "=", false], ["date" => "desc"]));
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
