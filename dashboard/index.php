<?php

session_start();

// Settings file locations.
$settings_file = '../dropplets/config/config-settings.php';
$template_file = '../dropplets/config/config-template.php';
$phpass_file   = '../dropplets/plugins/phpass-0.3/PasswordHash.php';
// Upload directory
$upload_dir    = '../posts/';

if (file_exists($settings_file)) {
    include($settings_file);
} else {
    header('Location: ../');
}

if (file_exists($template_file)) {
    include($template_file);
}
if (file_exists($phpass_file))
{
    include($phpass_file);
    $hasher  = new PasswordHash(8,FALSE);
}

/*-----------------------------------------------------------------------------------*/
/* User Machine
/*-----------------------------------------------------------------------------------*/

if (isset($_GET['action']))
{
    $action = $_GET['action'];
    switch ($action)
    {

        // Logging in.
        case 'login':
            if ((isset($_POST['password'])) && $hasher->CheckPassword($_POST['password'], $password)) {
                $_SESSION['user'] = true;

                // Redirect if authenticated.
                header('Location: ' . '../dashboard/');
            } else {
                
                // Display error if not authenticated.
                $login_error = 'Nope, try again!';
            }
            break;

        // Logging out.
        case 'logout':
            session_unset();
            session_destroy();

            // Redirect to dashboard on logout.
            header('Location: ' . '../dashboard/');
            break;
        
        // Fogot password.
        case 'forgot':
            
            // The verification file.
            $verification_file = "./verify.php";
            
            // If verified, allow a password reset.
            if (!isset($_GET["verify"])) {
            
                $code = sha1(md5(rand()));

                $verify_file_contents[] = "<?php";
                $verify_file_contents[] = "\$verification_code = \"" . $code . "\";";
                file_put_contents($verification_file, implode("\n", $verify_file_contents));

                $recovery_url = sprintf("%s/dashboard/index.php?action=forgot&verify=%s,", $blog_url, $code);
                $message      = sprintf("To reset your password go to: %s", $recovery_url);

                $headers[] = "From: " . $blog_email;
                $headers[] = "Reply-To: " . $blog_email;
                $headers[] = "X-Mailer: PHP/" . phpversion();

                mail($blog_email, $blog_title . " - Recover your Dropplets Password", $message, implode("\r\n", $headers));
                $login_error = "Details on how to recover your password have been sent to your email.";
            
            // If not verified, display a verification error.   
            } else {

                include($verification_file);

                if ($_GET["verify"] == $verification_code) {
                    $_SESSION["user"] = true;
                    unlink($verification_file);
                } else {
                    $login_error = "That's not the correct recovery code!";
                }
            }
            break;
        
        // Invalidation            
        case 'invalidate':
            if (!$_SESSION['user']) {
                $login_error = 'Nope, try again!';
            } else {
                if (!file_exists($upload_dir . 'cache/')) {
                    return;
                }
                
                $files = glob($upload_dir . 'cache/*');
                
                foreach ($files as $file) {
                    if (is_file($file))
                        unlink($file);
                }
            }
            
            header('Location: ' . '../dashboard/');
            break;
    }
}

/*-----------------------------------------------------------------------------------*/
/* Templates Machine
/*-----------------------------------------------------------------------------------*/

define('ACTIVE_TEMPLATE', $template);

function get_templates() {

    // The currently active template.
    $active_template = ACTIVE_TEMPLATE;

    // The templates directory.
    $templates_directory = '../templates/';

    // Get all templates in the templates directory.
    $available_templates = glob($templates_directory . '*');

    foreach ($available_templates as $template):

        // Generate template names.
        $template_dir_name = substr($template, 13);

        // Template screenshots.
        $template_screenshot = '' . $templates_directory . '' . $template_dir_name . '/screenshot.jpg'; {
            ?>
            <li>
                <img src="<?php echo $template_screenshot; ?>">

                <form method="POST" action="../dropplets/config/submit-template.php">
                    <div class="hidden">
                        <input type="text" name="template" id="template" required readonly value="<?php echo $template_dir_name ?>">
                    </div>
                    <button type="submit" name="submit" value="submit"></button>
                </form>
            </li>
        <?php
        }

    endforeach;
}

/*-----------------------------------------------------------------------------------*/
/* If Logged Out, Get the Login Panel
/*-----------------------------------------------------------------------------------*/

if (!isset($_SESSION['user'])) { ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>Login</title>
            <link rel="stylesheet" href="../dropplets/style/style.css" />
            <link rel="shortcut icon" href="../dropplets/style/images/favicon.png">
        </head>

        <body>
            <img src="../dropplets/style/images/logo.png" alt="Dropplets" />

            <h1>Login</h1>
            <p>Enter your password below to publish new posts, update existing posts, change your blog settings or select a new template for your blog.</p>


            <form method="POST" action="?action=login">
                <?php if (isset($login_error)): ?>
                <p class="error"><?php echo $login_error; ?></p>
                <?php endif; ?>

                <input type="password" name="password" id="password">

                <button type="submit" name="submit" value="submit"></button>
            </form>
            <p><a class="back" href="?action=forgot">Forget your password?</a> - <a class="back" href="<?php echo $blog_url; ?>">Back to "<?php echo $blog_title; ?>"</a></p>
        </body>
    </html>
<?php } else {

/*-----------------------------------------------------------------------------------*/
/* If Logged In, Go to the Dashboard
/*-----------------------------------------------------------------------------------*/
      
?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>Dashboard</title>
            <link rel="stylesheet" href="../dropplets/style/style.css" />
            <link rel="shortcut icon" href="../dropplets/style/images/favicon.png">
        </head>

        <body class="dashboard">
            <a class="slide settings" href="#settings"></a>
            <a class="home" href="../"></a>
            <a class="slide templates" href="#templates"></a>

            <div id="publish">
                <div id="dropbox">
                    <!-- Drag & Drop Publishing -->
                </div>

                <div id="loader">
                    <!-- The Publishing Loader -->
                </div>
            </div>

            <div id="settings" class="panel">
                <a class="settings-close" href="#settings"></a>

                <form method="POST" action="../dropplets/config/submit-settings.php">
                    <fieldset>
                        <div class="input">
                            <label>Blog Email</label>
                            <input type="text" name="blog_email" id="blog_email" required value="<?php echo $blog_email; ?>">
                        </div>

                        <div class="input">
                            <label>Blog Twitter ID</label>
                            <input type="text" name="blog_twitter" id="blog_twitter" required value="<?php echo $blog_twitter; ?>">
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="input hidden">
                            <input type="text" name="blog_url" id="blog_url" required readonly value="<?php echo $blog_url; ?>">
                        </div>

                        <div class="input">
                            <label>Blog Title</label>
                            <input type="text" name="blog_title" id="blog_title" required value="<?php echo $blog_title; ?>">
                        </div>

                        <div class="input">
                            <label>Blog Description</label>
                            <textarea name="meta_description" id="meta_description" rows="6" required><?php echo $meta_description; ?></textarea>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="input">
                            <label>Intro Title</label>
                            <input type="text" name="intro_title" id="intro_title" required value="<?php echo $intro_title; ?>">
                        </div>

                        <div class="input">
                            <label>Intro Text</label>
                            <textarea name="intro_text" id="intro_text" rows="12" required><?php echo $intro_text; ?></textarea>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="input">
                            <label>Password</label>
                            <input type="password" name="password" id="password" value="">
                        </div>
                    </fieldset>

                    <fieldset class="last">
                        <div class="input">
                            <label>Header Injection (e.g. Custom Styles)</label>
                            <textarea class="code" name="header_inject" id="header_inject" rows="12"><?php echo $header_inject; ?></textarea>
                        </div>

                        <div class="input">
                            <label>Footer Injection (e.g. Tracking Code)</label>
                            <textarea class="code" name="footer_inject" id="footer_inject" rows="12"><?php echo $footer_inject; ?></textarea>
                        </div>
                    </fieldset>

                    <button type="submit" name="submit" value="submit"></button>
                </form>
            </div>

            <div id="templates" class="panel">
                <a class="templates-close" href="#templates"></a>

                <ul>
                    <?php get_templates(); ?>
                </ul>

                <div id="arrows">
                    <a class="prev"></a>
                    <a class="next"></a>
                </div>
            </div>

            <a class="logout" href="?action=logout"></a>

            <script src="http://code.jquery.com/jquery-1.9.0.js"></script>
            <script src="script.js"></script>

            <script>
                $(function() {

                    // Templates
                    var active = 0;
                    var list = $("ul");

                    list.children("li").eq("0").siblings().hide();

                    $(".next").bind("click", function() {
                        active = active == list.children("li").length - 1 ? 0 : active + 1;
                    });

                    $(".prev").bind("click", function() {
                        active = active == 0 ? list.children("li").length - 1 : active - 1;
                    });


                    var getActive = function() {
                        return list.children("li").eq(active);
                    };

                    $(".prev,.next").bind("click", function() {
                        getActive().show().siblings().hide();
                    });


                    // Panels
                    $(".settings").click(function() {
                        $("#settings").animate({
                            bottom: "0px"
                        });
                        $("#settings button").animate({
                            bottom: "0px"
                        });
                        $("#templates").animate({
                            bottom: "-2000px"
                        });
                        $("#templates button").animate({
                            bottom: "-140px"
                        });
                        return false;
                    });

                    $(".settings-close").click(function() {
                        $("#settings").animate({
                            bottom: "-2000px"
                        });
                        $("#settings button").animate({
                            bottom: "-140px"
                        });
                        return false;
                    });

                    $(".templates").click(function() {
                        $("#templates").animate({
                            bottom: "0px"
                        });
                        $("#templates button").animate({
                            bottom: "0px"
                        });
                        $("#settings").animate({
                            bottom: "-2000px"
                        });
                        $("#settings button").animate({
                            bottom: "-140px"
                        });
                        return false;
                    });

                    $(".templates-close").click(function() {
                        $("#templates").animate({
                            bottom: "-2000px"
                        });
                        $("#templates button").animate({
                            bottom: "-140px"
                        });
                        return false;
                    });
                });
            </script>
        </body>
    </html>
<?php } ?>