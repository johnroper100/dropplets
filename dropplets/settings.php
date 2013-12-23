<?php 

/*-----------------------------------------------------------------------------------*/
/* Debug Mode
/*-----------------------------------------------------------------------------------*/

$display_errors = true;

// Display errors if there are any.
ini_set('display_errors', $display_errors);

/*-----------------------------------------------------------------------------------*/
/* Post Cache ('on' or 'off')
/*-----------------------------------------------------------------------------------*/

$post_cache = 'off';
$index_cache = 'off';

/*-----------------------------------------------------------------------------------*/
/* Configuration & Options
/*-----------------------------------------------------------------------------------*/

include('./config.php');
// encode/decode config variables
include_once('./dropplets/encdec.php');

// Definitions from the included configs above.
define('BLOG_EMAIL', $blog_email);
define('BLOG_TWITTER', $blog_twitter);
define('TWITTER_CKEY', Decode($password,$consumerkey));
define('TWITTER_CSECRET', Decode($password,$consumersecret));
define('TWITTER_TOKEN', Decode($password,$accesstoken));
define('TWITTER_TSECRET', Decode($password,$accesstokensecret));
define('BLOG_FACEBOOK', $blog_facebook);
define('BLOG_GOOGLEP', $blog_googlep);
define('BLOG_URL', $blog_url);
define('BLOG_TITLE', $blog_title);
define('META_DESCRIPTION', $meta_description);
define('INTRO_TITLE', trim($intro_title));
define('INTRO_TEXT', trim($intro_text));
define('HEADER_INJECT', stripslashes(trim($header_inject)));
define('FOOTER_INJECT', stripslashes(trim($footer_inject)));
define('ACTIVE_TEMPLATE', $template);
define('language_default', $language_default);
/*-----------------------------------------------------------------------------------*/
/* Definitions (These Should Be Moved to "Settings")
/*-----------------------------------------------------------------------------------*/

if(isset($_COOKIE['i18nLanguage'])) { 
	$language = $_COOKIE['i18nLanguage']; 
} else {
	$language = $language_default; // moved to config - see i18n-dropplets.php plugin for more informations
}
$feed_max_items = '10';
$date_format = '%B %d, %Y';
// moved to index.php - Use language pack now - $error_title = "Sorry, But That's Not Here";
// moved to index.php - Use language pack now - $error_text = "Really sorry, but what you're looking for isn't here. Click the button below to find something else that might interest you.";

// setlocale(LC_ALL, ''); //see i18n-dropplets.php plugin for more informations


/*-----------------------------------------------------------------------------------*/
/* Post Configuration
/*-----------------------------------------------------------------------------------*/

$pagination_on_off = "on"; //Infinite scroll by default?
define('PAGINATION_ON_OFF', $pagination_on_off);

$posts_per_page = 4;
define('POSTS_PER_PAGE', $posts_per_page);

$infinite_scroll = "off"; //Infinite scroll works only if pagination is on.
define('INFINITE_SCROLL', $infinite_scroll);

$post_directory = './posts/';
$cache_directory = './posts/cache/';

if (glob($post_directory . '*.md') != false)
{
    $posts_dir = './posts/';
} else {
    $posts_dir = './dropplets/welcome/';
}

// Definitions from the settings above.
define('POSTS_DIR', $posts_dir);
define('CACHE_DIR', $cache_directory);
define('FILE_EXT', '.md');

/*-----------------------------------------------------------------------------------*/
/* Cache Configuration
/*-----------------------------------------------------------------------------------*/


if (isset($_SESSION['user'])) { //not show warning or error if the element is not set
	//no caching if user is logged in
	if ( $_SESSION['user'] ) {
		$post_cache = 'off';
		$index_cache = 'off';
	}
}

if (!file_exists(CACHE_DIR) && ($post_cache != 'off' || $index_cache != 'off')) {
	mkdir(CACHE_DIR,0755,TRUE);
}

/*-----------------------------------------------------------------------------------*/
/* Template Files
/*-----------------------------------------------------------------------------------*/

// Get the active template directory.
$template_dir = './templates/' . $template . '/';
$template_dir_url = $blog_url . 'templates/' . $template . '/';

// Get the active template files.
$index_file = $template_dir . 'index.php';
$post_file = $template_dir . 'post.php';
$posts_file = $template_dir . 'posts.php';
$not_found_file = $template_dir . '404.php';
