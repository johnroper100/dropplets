<?php

if (file_exists('./config.php')) {

/*-----------------------------------------------------------------------------------*/
/* Debug Mode
/*-----------------------------------------------------------------------------------*/

$display_errors = false;

/*-----------------------------------------------------------------------------------*/
/* Configuration & Options
/*-----------------------------------------------------------------------------------*/

include('./config.php');

// A few definitions.
$language = 'en-us';
$feed_max_items = '10';
$date_format = 'F jS, Y';
$error_title = 'Sorry, But That&#8217;s Not Here';
$error_text = 'Really sorry, but what you&#8217;re looking for isn&#8217;t here. Click the button below to find something else that might interest you.';

/*-----------------------------------------------------------------------------------*/
/* Post Configuration
/*-----------------------------------------------------------------------------------*/

$directory = '../posts/';

if (glob($directory . '*.txt') != false)
{
    $posts_dir = '../posts/';
} else {
    $posts_dir = '../dropplets/welcome/';
}

define('POSTS_DIR', $posts_dir);
define('FILE_EXT', '.txt');

/*-----------------------------------------------------------------------------------*/
/* Template Files
/*-----------------------------------------------------------------------------------*/

$template_dir = '../template/';
$stylesheet_dir = $site_url . '/template/';
$custom_dir = $template_dir . 'custom/';

// define the default locations of the template files
$index_file = $template_dir . 'index.php';
$intro_file = $template_dir . 'intro.php';
$post_file = $template_dir . 'post.php';
$posts_file = $template_dir . 'posts.php';
$not_found_file = $template_dir . '404.php';

if (file_exists($custom_dir . 'style.css')) {

    $custom_glob = glob($custom_dir . '*');

    // if a custom template exists, look for versions of the template files and use the ones from custom
    if ( in_array($custom_dir . '404.php', $custom_glob) ) {
        $not_found_file = $custom_dir . '404.php';
    }
    if ( in_array($custom_dir . 'index.php', $custom_glob) ) {
        $index_file = $custom_dir . 'index.php';
    }
    if ( in_array($custom_dir . 'intro.php', $custom_glob) ) {
        $intro_file = $custom_dir . 'intro.php';
    }
    if ( in_array($custom_dir . 'post.php', $custom_glob) ) {
        $post_file = $custom_dir . 'post.php';
    }
    if ( in_array($custom_dir . 'posts.php', $custom_glob) ) {
        $posts_file = $custom_dir . 'posts.php';
    }
    $stylesheet_dir = $site_url . '/template/custom/';
}

/*-----------------------------------------------------------------------------------*/
/* Let's Get Started
/*-----------------------------------------------------------------------------------*/

// Display errors if there are any.
ini_set('display_errors', $display_errors);

// Get required plugins.
include_once './plugins/feedwriter.php';
include_once './plugins/markdown.php';

// Reading file names.
if (empty($_GET['filename'])) {
    $filename = NULL;
} else if($_GET['filename'] == 'rss' || $_GET['filename'] == 'atom') {
    $filename = $_GET['filename'];
} else {
    $filename = POSTS_DIR . $_GET['filename'] . FILE_EXT;
}

/*-----------------------------------------------------------------------------------*/
/* The Home Page
/*-----------------------------------------------------------------------------------*/

if ($filename==NULL) {
    $posts = get_all_posts();
    if($posts) {
        ob_start();
        $content = '';
        foreach($posts as $post) {
        
            // The site title
            $site_title = $title;
            
            // The post title.
            $post_title = $post['title'];
            
            // The published ISO date.
            $published_iso_date = $post['time'];
                        
            // The published date.
            $published_date = date_format(date_create($published_iso_date), $date_format);
            
            // The post category.
            $post_category = $post['category'];
            
            // The post intro.
            $post_intro = $post['intro'];
            
            // The post link.
            $post_link = str_replace(FILE_EXT, '', $post['fname']);
            
            // The post image.
            $image = str_replace(array(FILE_EXT), '', POSTS_DIR.$post['fname']).'.jpg';
            
            if (file_exists($image)) {
                $post_image = $site_url.'/'.str_replace(array(FILE_EXT, '../'), '', POSTS_DIR.$post['fname']).'.jpg';
            } else {
                $post_image = $site_url.'/dropplets/images/logo.png';
            }
            
            // Grab the site intro template file.
            include_once $intro_file;
            
            // Grab the milti-post template file.
            include $posts_file;   
        }
        echo $content;
        $content = ob_get_contents();

        ob_end_clean();
    } else {
        ob_start();
        
        // The site title
        $site_title = $error_title;
        
        // Get the 404 page template.
        $post = Markdown(file_get_contents($not_found_file));
        
        include $post_file;
        $content = ob_get_contents();
        ob_end_clean();
    }
    
    // Get the index template file.
    include_once $index_file;
} 

/*-----------------------------------------------------------------------------------*/
/* RSS Feed
/*-----------------------------------------------------------------------------------*/

else if ($filename == 'rss' || $filename == 'atom') {
    ($filename=='rss') ? $feed = new FeedWriter(RSS2) : $feed = new FeedWriter(ATOM);

    $feed->setTitle($title);
    $feed->setLink($site_url);

    if($filename=='rss') {
        $feed->setDescription($site->meta_description);
        $feed->setChannelElement('language', $language);
        $feed->setChannelElement('pubDate', date(DATE_RSS, time()));
    } else {
        $feed->setChannelElement('author', $author.' - ' . $email);
        $feed->setChannelElement('updated', date(DATE_RSS, time()));
    }

    $posts = get_all_posts();

    if($posts) {
        $c=0;
        foreach($posts as $post) {
            if($c<$feed_max_items) {
                $item = $feed->createNewItem();
                $item->setTitle($post['title']);
                $item->setLink(rtrim($site_url, '/').'/'.str_replace(FILE_EXT, '', $post['fname']));
                $item->setDate($post['time']);
                $item->setDescription(Markdown(file_get_contents(rtrim(POSTS_DIR, '/').'/'.$post['fname'])));
                if($filename=='rss') {
                    $item->addElement('author', $author.' - ' . $email);
                    $item->addElement('guid', rtrim($site_url, '/').'/'.str_replace(FILE_EXT, '', $post['fname']));
                }
                $feed->addItem($item);
                $c++;
            }
        }
    }
    $feed->genarateFeed();
} 

/*-----------------------------------------------------------------------------------*/
/* Single Post Pages
/*-----------------------------------------------------------------------------------*/

else {
    ob_start();
    
    // The post file.
    $fcontents = file($filename);
    
    // The cached file.
    $cachefile = str_replace(array(FILE_EXT), '', $filename).'.html';
    
    // If there's no file for the selected permalink, grab the 404 page template.
    if (!file_exists($filename)) {
    
        // The site title
        $site_title = $error_title;
    
        // Get the 404 page template.
        include $not_found_file;
    
    // If there is a cached file for the selected permalink, display the cached post.  
    } else if (file_exists($cachefile)) {
    
        // The site title
        $site_title = str_replace('# ', '', $fcontents[0]);
        
        // Get the cached post.
        include $cachefile;
    
    // If there is a file for the selected permalink, display and cache the post.
    } else {
    
        // The site title
        $site_title = str_replace('# ', '', $fcontents[0]);
        
        // The post title.
        $post_title = str_replace('# ', '', $fcontents[0]);
        
        // The published date.
        $published_iso_date = str_replace('-', '', $fcontents[1]);
                
        // The published date.
        $published_date = date_format(date_create($published_iso_date), $date_format);
        
        // The post category.
        $post_category = str_replace('-', '', $fcontents[2]);
        
        // The post link.
        $post_link = $site_url.'/'.str_replace(array(FILE_EXT, POSTS_DIR), '', $filename);
        
        // The post image.
        $image = str_replace(array(FILE_EXT), '', $filename).'.jpg';
        
        if (file_exists($image)) {
            $post_image = $site_url.'/'.str_replace(array(FILE_EXT, '../'), '', $filename).'.jpg';
        } else {
            $post_image = $site_url.'/dropplets/images/logo.png';
        }
        
        // The post.
        $post = Markdown(join('', $fcontents));
        
        // Get the post template file.
        include $post_file;
        
        // Cache the post
        $fp = fopen($cachefile, 'w'); 
        fwrite($fp, ob_get_contents()); 
        fclose($fp);
    }
    $content = ob_get_contents();
    ob_end_clean();
    
    // Get the index template file.
    include_once $index_file;
}

/*-----------------------------------------------------------------------------------*/
/* Run Setup if No Config
/*-----------------------------------------------------------------------------------*/

} else { 

// Fetch the current url.
$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$params = $_SERVER['QUERY_STRING'];
$current_url = $protocol . '://' . $host . $script . '?' . $params;
$url = str_replace('/dropplets/index.php?filename=', '', $current_url);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Let's Get Started</title>
        <link rel="stylesheet" href="./dropplets/style-setup.css" />
    </head>
    
    <body>
        <img src="./dropplets/images/logo.png" alt="Dropplets" />
        
        <h1>Let's Get Started</h1>
        <p>With Dropplets, there's no admin, no database, just simple blogging goodness. To get started, enter your site information below (all fields are required) and then click the check mark at the bottom. That's all there's to it :)</p>
        
		<form method="POST" action="./dropplets/setup.php">
		    <fieldset>
		        <div class="input">
		            <input type="text" name="author" id="author" required placeholder="Your Name">
		        </div> 
		        
		        <div class="input">
		            <input type="text" name="email" id="email" required placeholder="Your Email Address">
		        </div> 
		        
		        <div class="input">
		            <input type="text" name="twitter" id="twitter" required placeholder="Your Twitter ID (e.g. &quot;dropplets&quot;)">
		        </div> 
		    </fieldset>
		    
		    <fieldset>
    		    <div class="input hidden">
    		        <input type="text" name="site_url" id="site_url" required readonly value="<?php echo($url) ?>">
    		    </div>
    		    
    		    <div class="input">
    		        <input type="text" name="title" id="title" required placeholder="Your Site Title">
    		    </div>
    		    
    		    <div class="input">
    		        <textarea name="meta_description" id="meta_description" rows="6" required placeholder="Add your site description here. This should be a short sentance that describes what your blog is going to be about."></textarea>
    		    </div> 
		    </fieldset>
		    
		    <fieldset>
    		    <div class="input">
    		        <input type="text" name="intro_title" id="intro_title" required placeholder="Your Intro Title">
    		    </div> 
    		    
    		    <div class="input">
    		        <textarea name="intro_text" id="intro_text" rows="12" required placeholder="Add your intro text here. The &quot;intro&quot; displayed at the top of the home page of your blog and is generally intended to introduce who you are to your readers, kind of like an &quot;about&quot; page."></textarea>
    		    </div> 
		    </fieldset>
		    
            <fieldset>
                <div>
                    <input type="text" name="username" id="username" required placeholder="Enter a Username">
                </div>
            </fieldset>
            
		    <fieldset>
    		    <div class="input">
    		        <input type="password" name="password" id="password" required placeholder="Enter a Password">
    		    </div>
		    </fieldset>
		    
		    <button type="submit" name="submit" value="submit" class="btn btn-info"></button>
		</form>
    </body>
</html>

<?php }

/*-----------------------------------------------------------------------------------*/
/* Get All Posts Function (Used For the Home Page Above)
/*-----------------------------------------------------------------------------------*/

function get_all_posts() {
    global $dropplets;
    
    if($handle = opendir(POSTS_DIR)) {
        
        $files = array();
        $filetimes = array();
        
        while (false !== ($entry = readdir($handle))) {
            if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {
                
                // The post file.
                $fcontents = file(POSTS_DIR.$entry);
                
                // The post title.
                $title = str_replace('# ', '', $fcontents[0]);
                                
                // The published date.
                $time = str_replace('-', '', $fcontents[1]);
                
                // The post category.
                $category = str_replace('-', '', $fcontents[2]);
                
                // The post intro.
                $intro = Markdown($fcontents[4]);
                
                $files[] = array('time' => $time, 'fname' => $entry, 'title' => $title, 'category' => $category, 'intro' => $intro);
                $filetimes[] = $time;
                $titles[] = $title;
                $categories[] = $category;
                $introductions[] = $intro;
            }
        }
        array_multisort($filetimes, SORT_DESC, $files);
        return $files;
        
    } else {
        return false;
    }
}