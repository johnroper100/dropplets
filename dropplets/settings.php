<?php 

/*-----------------------------------------------------------------------------------*/
/* Debug Mode
/*-----------------------------------------------------------------------------------*/

$display_errors = false;

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

// Definitions from the included configs above.
define('BLOG_EMAIL', $blog_email);
define('BLOG_TWITTER', $blog_twitter);
define('BLOG_URL', $blog_url);
define('BLOG_TITLE', $blog_title);
define('META_DESCRIPTION', $meta_description);
define('INTRO_TITLE', $intro_title);
define('INTRO_TEXT', $intro_text);
define('HEADER_INJECT', stripslashes($header_inject));
define('FOOTER_INJECT', stripslashes($footer_inject));
define('ACTIVE_TEMPLATE', $template);

/*-----------------------------------------------------------------------------------*/
/* Definitions (These Should Be Moved to "Settings")
/*-----------------------------------------------------------------------------------*/

$language = 'en-us';
$feed_max_items = '10';
$date_format = 'F jS, Y';
$error_title = 'Sorry, But That&#8217;s Not Here';
$error_text = 'Really sorry, but what you&#8217;re looking for isn&#8217;t here. Click the button below to find something else that might interest you.';

/*-----------------------------------------------------------------------------------*/
/* Post Configuration
/*-----------------------------------------------------------------------------------*/

$pagination_on_off = "off"; //Infinite scroll by default?
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

//no caching if user is logged in
if ( $_SESSION['user'] ) {
	$post_cache = 'off';
	$index_cache = 'off';
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
