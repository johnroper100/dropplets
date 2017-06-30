<?php

/*-----------------------------------------------------------------------------------*/
/* Include 3rd Party Functions
/*-----------------------------------------------------------------------------------*/

include('./dropplets/includes/feedwriter.php');
include('./dropplets/includes/php-markdown-lib/Michelf/MarkdownExtra.inc.php');
include('./dropplets/includes/phpass.php');
include('./dropplets/includes/spyc.php');
include('./dropplets/includes/actions.php');

// Markdown wrapper
use \Michelf\MarkdownExtra;
function Markdown($txt) {
    return MarkdownExtra::defaultTransform($txt);
}

/*-----------------------------------------------------------------------------------*/
/* User Machine
/*-----------------------------------------------------------------------------------*/

// Password hashing via phpass.
$hasher  = new PasswordHash(8,FALSE);

// Define Login Error variable.
$login_error = NULL;

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
                header('Location: ' . './');
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
            header('Location: ' . './');
            break;

        // Fogot password.
        case 'forgot':

            // The verification file.
            $verification_file = "./verify.php";

            // If verified, allow a password reset.
            if (isset($_GET["verify"])) {

                require($verification_file);

                if ($_GET["verify"] === $verification_code) {
                    $_SESSION["user"] = true;
                    unlink($verification_file);
                } else {
                    $login_error = "That's not the correct recovery code!";
                }
            }
            else {
                // Generate verification token and send e-mail
                $code = sha1(md5(rand()));

                $verify_file_contents[] = "<?php";
                $verify_file_contents[] = "\$verification_code = \"" . $code . "\";";
                file_put_contents($verification_file, implode("\n", $verify_file_contents));

                $recovery_url = sprintf("%s/index.php?action=forgot&verify=%s,", $blog_url, $code);
                $message      = sprintf("To reset your password go to: %s", $recovery_url);

                $headers[] = "From: " . $blog_email;
                $headers[] = "Reply-To: " . $blog_email;
                $headers[] = "X-Mailer: PHP/" . phpversion();

                mail($blog_email, $blog_title . " - Recover your Dropplets Password", $message, implode("\r\n", $headers));
                $login_error = "Details on how to recover your password have been sent to your email.";
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

            header('Location: ' . './');
            break;
    }

}

define('LOGIN_ERROR', $login_error);

/*-----------------------------------------------------------------------------------*/
/* Get All Posts Function
/*-----------------------------------------------------------------------------------*/

function get_all_posts($options = array()) {
    global $dropplets;

    if($handle = opendir(POSTS_DIR)) {

        $files = array();
        $filetimes = array();

        while (false !== ($entry = readdir($handle))) {
            if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {

                // Define the post file.
                $fcontents = file(POSTS_DIR.$entry);

                // Define the post title.
                $post_title = Markdown($fcontents[0]);

                // Define the post author.
                $post_author = str_replace(array("\n", '-'), '', $fcontents[1]);

                // Define the post author Twitter account.
                $post_author_twitter = str_replace(array("\n", '- '), '', $fcontents[2]);

                // Define the published date.
                $post_date = str_replace('-', '', $fcontents[3]);

                // Define the post categories.
                $post_categories = explode(',', str_replace(array("\n", '-'), '', $fcontents[4]));
                $post_categories = array_map(function($el) { return trim($el); }, $post_categories);

                // Early return if we only want posts from a certain category
                if(!empty($options["category"]) && !in_array(strtolower($options["category"]), array_map('strtolower', $post_categories))) {
                    continue;
                }

                // Define the post status.
                $post_status = str_replace(array("\n", '- '), '', $fcontents[5]);

                // Define the post intro.
                $post_intro = Markdown($fcontents[7]);

                // Define the post content
                $post_content = Markdown(join('', array_slice($fcontents, 6, count($fcontents) -1)));

                // Pull everything together for the loop.
                $files[] = array('fname' => $entry, 'post_title' => $post_title, 'post_author' => $post_author, 'post_author_twitter' => $post_author_twitter,
'post_date' => $post_date, 'post_categories' => $post_categories, 'post_status' => $post_status, 'post_intro' => $post_intro, 'post_content' => $post_content);
                $post_dates[] = $post_date;
                $post_titles[] = $post_title;
                $post_authors[] = $post_author;
                $post_authors_twitter[] = $post_author_twitter;
                $post_categories[] = $post_categories;
                $post_statuses[] = $post_status;
                $post_intros[] = $post_intro;
                $post_contents[] = $post_content;
            }
        }
        array_multisort($post_dates, SORT_DESC, $files);
        return $files;

    } else {
        return false;
    }
}

/*-----------------------------------------------------------------------------------*/
/* Get Posts for Selected Category
/*-----------------------------------------------------------------------------------*/

function get_posts_for_category($category) {
    $category = trim(strtolower($category));
    return get_all_posts(array("category" => $category));
}

/*-----------------------------------------------------------------------------------*/
/* Post Pagination
/*-----------------------------------------------------------------------------------*/

function get_pagination($page,$total) {

    $string = '';
    $string .= "<ul style=\"list-style:none; width:400px; margin:15px auto;\">";

    for ($i = 1; $i<=$total;$i++) {
        if ($i == $page) {
            $string .= "<li style='display: inline-block; margin:5px;' class=\"active\"><a class=\"button\" href='#'>".$i."</a></li>";
        } else {
            $string .=  "<li style='display: inline-block; margin:5px;'><a class=\"button\" href=\"?page=".$i."\">".$i."</a></li>";
        }
    }

    $string .= "</ul>";
    return $string;
}

/*-----------------------------------------------------------------------------------*/
/* Get Installed Templates
/*-----------------------------------------------------------------------------------*/

function get_installed_templates() {

    // The currently active template.
    $active_template = ACTIVE_TEMPLATE;

    // The templates directory.
    $templates_directory = './templates/';

    // Get all templates in the templates directory.
    $available_templates = glob($templates_directory . '*');

    foreach ($available_templates as $template):

        // Generate template names.
        $template_dir_name = substr($template, 12);

        // Template screenshots.
        $template_screenshot = '' . $templates_directory . '' . $template_dir_name . '/screenshot.jpg'; {
            ?>
            <li<?php if($active_template == $template_dir_name) { ?> class="active"<?php } ?>>
                <div class="shadow"></div>
                <form method="POST" action="./dropplets/save.php">
                    <img src="<?php echo $template_screenshot; ?>">
                    <input type="hidden" name="template" id="template" required readonly value="<?php echo $template_dir_name ?>">
                    <button class="<?php if ($active_template == $template_dir_name) :?>active<?php else : ?>activate<?php endif; ?>" type="submit" name="submit" value="submit"><?php if ($active_template == $template_dir_name) :?>t<?php else : ?>k<?php endif; ?></button>
                </form>
            </li>
        <?php
        }
    endforeach;
}

/*-----------------------------------------------------------------------------------*/
/* If is Home (Could use "is_single", "is_category" as well.)
/*-----------------------------------------------------------------------------------*/

$homepage = BLOG_URL;

// Get the current page.
$currentpage  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] : 'https://'.$_SERVER["SERVER_NAME"];
$currentpage .= $_SERVER["REQUEST_URI"];

// If is home.
$is_home = ($homepage==$currentpage);
define('IS_HOME', $is_home);

/*-----------------------------------------------------------------------------------*/
/* Get Profile Image
/*-----------------------------------------------------------------------------------*/

function get_twitter_profile_img($username) {

    // Pattern for avatar loading service.
    $avatar_service_pat = 'http://avatars.io/twitter/:username?size=large';
	
	// Get the cached profile image.`
    $cache = IS_CATEGORY ? '' : '';
    $array = explode('/category/', $_SERVER['REQUEST_URI']);
    $array = (count($array) > 1) ? explode('/', $array[1]) : $array;
    if(count($array)!=1) $cache .= './.';
    $cache .= './cache/';
	$profile_image = $cache.$username.'.jpg';

	// Cache the image if it doesn't already exist.
	if (!file_exists($profile_image)) {
	    $image_url = str_replace(':username', $username, $avatar_service_pat);
	    $image = file_get_contents($image_url);
	    file_put_contents('./cache/'.$username.'.jpg', $image);
	}

	// Return the image URL.
	return $profile_image;
}

/*-----------------------------------------------------------------------------------*/
/* Get Author Info
/*-----------------------------------------------------------------------------------*/

function get_author($author_id, $options=array('posts' => TRUE)){
  $author_id = trim($author_id);
  $author_file = './authors/'.$author_id.'.yml';

  if(file_exists($author_file)){
    $author = Spyc::YAMLLoad($author_file);
    $author['handle'] = $author_id;
    $author['_found'] = TRUE;

    $author['url'] = BLOG_URL."author/".$author_id;

    if($author['email']){
      $author['avatar'] = 'https://gravatar.com/avatar/'.md5($author['email']).'?s=180';
    }

    if($options["posts"]){
      $author_posts = array();
      foreach(get_all_posts() as $post){
        if(trim($post['post_author']) == $author_id){

          $post['url'] = BLOG_URL.$blog_url.str_replace(array(FILE_EXT, POSTS_DIR), '', $post['fname']);
          $post['post_category_link'] = BLOG_URL.'category/'.urlencode(trim(strtolower($post['post_category'])));
          array_push($author_posts, $post);
        }
      }

      $author['posts'] = $author_posts;
    }

    return $author;
  }

  return array("name" => $author_id, "handle" => $author_id, "_found" => FALSE);
}


/*-----------------------------------------------------------------------------------*/
/* Include All Plugins in Plugins Directory
/*-----------------------------------------------------------------------------------*/

foreach(glob('./plugins/' . '*.php') as $plugin){
    include_once $plugin;
}

/*-----------------------------------------------------------------------------------*/
/* Dropplets Header
/*-----------------------------------------------------------------------------------*/

function get_header() { ?>
    <!-- RSS Feed Links -->
    <link rel="alternate" type="application/rss+xml" title="Subscribe using RSS" href="<?php echo BLOG_URL; ?>rss" />
    <link rel="alternate" type="application/atom+xml" title="Subscribe using Atom" href="<?php echo BLOG_URL; ?>atom" />

    <!-- Dropplets Styles -->
    <link rel="stylesheet" href="<?php echo BLOG_URL; ?>dropplets/style/style.css">
    <link rel="shortcut icon" href="<?php echo BLOG_URL; ?>dropplets/style/images/favicon.png">

    <!-- User Header Injection -->
    <?php echo HEADER_INJECT; ?>

    <!-- Plugin Header Injection -->
    <?php action::run('dp_header'); ?>
<?php

}

/*-----------------------------------------------------------------------------------*/
/* Dropplets Footer
/*-----------------------------------------------------------------------------------*/

function get_footer() { ?>
    <!-- jQuery & Required Scripts -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

    <?php if (PAGINATION_ON_OFF !== "off") { ?>
    <!-- Post Pagination -->
    <script>
        var infinite = true;
        var next_page = 2;
        var loading = false;
        var no_more_posts = false;
        $(function() {
            function load_next_page() {
                $.ajax({
                    url: "index.php?page=" + next_page,
                    success: function (res) {
                        next_page++;
                        var result = $.parseHTML(res);
                        var articles = $(result).filter(function() {
                            return $(this).is('article');
                        });
                        if (articles.length < 2) {  //There's always one default article, so we should check if  < 2
                            no_more_posts = true;
                        }  else {
                            $('body').append(articles.slice(1));
                        }
                        loading = false;
                    }
                });
            }

            $(window).scroll(function() {
                var when_to_load = $(window).scrollTop() * 0.32;
                if (infinite && (loading != true && !no_more_posts) && $(window).scrollTop() + when_to_load > ($(document).height()- $(window).height() ) ) {
                    // Sometimes the scroll function may be called several times until the loading is set to true.
                    // So we need to set it as soon as possible
                    loading = true;
                    setTimeout(load_next_page,500);
                }
            });
        });
    </script>
    <?php } ?>

    <!-- Dropplets Tools -->
    <?php include('./dropplets/tools.php'); ?>

    <!-- User Footer Injection -->
    <?php echo FOOTER_INJECT; ?>

    <!-- Plugin Footer Injection -->
    <?php action::run('dp_footer'); ?>
<?php

}
