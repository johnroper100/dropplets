<?php

if (file_exists('./dropplets/config/config-settings.php')) {

/*-----------------------------------------------------------------------------------*/
/* Debug Mode
/*-----------------------------------------------------------------------------------*/

$display_errors = false;

/*-----------------------------------------------------------------------------------*/
/* Post Cache ('on' or 'off')
/*-----------------------------------------------------------------------------------*/

$post_cache = 'off';

/*-----------------------------------------------------------------------------------*/
/* Configuration & Options
/*-----------------------------------------------------------------------------------*/

include('./dropplets/config/config-settings.php');
include('./dropplets/config/config-template.php');

// A few definitions.
$language = 'en-us';
$feed_max_items = '10';
$date_format = 'F jS, Y';
$error_title = 'Sorry, But That&#8217;s Not Here';
$error_text = 'Really sorry, but what you&#8217;re looking for isn&#8217;t here. Click the button below to find something else that might interest you.';
$powered_by = '<a class="powered-by" href="http://dropplets.com" target="_blank"><img src="' . $blog_url . '/dropplets/style/images/powered-by.png" />Powered by Dropplets</a>';

/*-----------------------------------------------------------------------------------*/
/* Post Configuration
/*-----------------------------------------------------------------------------------*/

$post_directory = './posts/';

if (glob($post_directory . '*.md') != false)
{
    $posts_dir = './posts/';
} else {
    $posts_dir = './dropplets/welcome/';
}

define('POSTS_DIR', $posts_dir);
define('FILE_EXT', '.md');

/*-----------------------------------------------------------------------------------*/
/* Template Files
/*-----------------------------------------------------------------------------------*/

$template_dir = './templates/' . $template . '/';
$template_dir_url = $blog_url . '/templates/' . $template . '/';

// define the default locations of the template files
$index_file = $template_dir . 'index.php';
$intro_file = $template_dir . 'intro.php';
$post_file = $template_dir . 'post.php';
$posts_file = $template_dir . 'posts.php';
$not_found_file = $template_dir . '404.php';

/*-----------------------------------------------------------------------------------*/
/* Let's Get Started
/*-----------------------------------------------------------------------------------*/

// Display errors if there are any.
ini_set('display_errors', $display_errors);

// Get required plugins.
include_once './dropplets/plugins/feedwriter.php';
include_once './dropplets/plugins/markdown.php';

// Reading file names.
if (empty($_GET['filename'])) {
    $filename = NULL;
} else if($_GET['filename'] == 'rss' || $_GET['filename'] == 'atom') {
    $filename = $_GET['filename'];
} else {
    $filename = POSTS_DIR . $_GET['filename'] . FILE_EXT;
}

/*-----------------------------------------------------------------------------------*/
/* The Home Page (All Posts)
/*-----------------------------------------------------------------------------------*/

if ($filename==NULL) {
    $posts = get_all_posts();
    if($posts) {
        ob_start();
        $content = '';
        foreach($posts as $post) {
        
            // The site title
            $site_title = $blog_title;
            
            // The post title.
            $post_title = $post['title'];
            
            // The post author.
            $post_author = $post['post_author'];
            
            // The post author twitter id.
            $post_author_twitter = $post['post_author_twitter'];
            
            // The published ISO date.
            $published_iso_date = $post['time'];
                        
            // The published date.
            $published_date = date_format(date_create($published_iso_date), $date_format);
            
            // The post category.
            $post_category = $post['category'];
            
            // The post category.
            $post_status = $post['post_status'];
            
            // The post intro.
            $post_intro = $post['intro'];
            
            // The post link.
            $post_link = str_replace(FILE_EXT, '', $post['fname']);
            
            // The post image.
            $image = str_replace(array(FILE_EXT), '', POSTS_DIR.$post['fname']).'.jpg';
            
            if (file_exists($image)) {
                $post_image = $blog_url.'/'.str_replace(array(FILE_EXT, '../'), '', POSTS_DIR.$post['fname']).'.jpg';
            } else {
                $post_image = 'https://api.twitter.com/1/users/profile_image?screen_name='.$post_author_twitter.'&size=bigger';
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

    $feed->setTitle($blog_title);
    $feed->setLink($blog_url);

    if($filename=='rss') {
        $feed->setDescription($meta_description);
        $feed->setChannelElement('language', $language);
        $feed->setChannelElement('pubDate', date(DATE_RSS, time()));
    } else {
        $feed->setChannelElement('author', $blog_title.' - ' . $blog_email);
        $feed->setChannelElement('updated', date(DATE_RSS, time()));
    }

    $posts = get_all_posts();

    if($posts) {
        $c=0;
        foreach($posts as $post) {
            if($c<$feed_max_items) {
                $item = $feed->createNewItem();
                
                // Quick & dirty hack to remove HTML 
                $item->setTitle(substr($post['title'], 4, -6));
                $item->setLink(rtrim($blog_url, '/').'/'.str_replace(FILE_EXT, '', $post['fname']));
                $item->setDate($post['time']);

				$remove_metadata_from = file(rtrim(POSTS_DIR, '/').'/'.$post['fname']);
                if($filename=='rss') {
                    $item->addElement('author', str_replace('-', '', $remove_metadata_from[1]).' - ' . $blog_email);
                    $item->addElement('guid', rtrim($blog_url, '/').'/'.str_replace(FILE_EXT, '', $post['fname']));
                }
				// Another quick & dirty hack to remove the metadata from the RSS feed.
				unset($remove_metadata_from[0], $remove_metadata_from[1], $remove_metadata_from[2], $remove_metadata_from[3], $remove_metadata_from[4], $remove_metadata_from[5]);
				$remove_metadata_from = array_values($remove_metadata_from);

                $item->setDescription(Markdown(implode($remove_metadata_from)));

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
        
        // The post author.
        $post_author = str_replace('-', '', $fcontents[1]);
        
        // The post author Twitter account.
        $post_author_twitter = str_replace('- ', '', $fcontents[2]);
        
        // The published date.
        $published_iso_date = str_replace('-', '', $fcontents[3]);
                
        // The published date.
        $published_date = date_format(date_create($published_iso_date), $date_format);
        
        // The post category.
        $post_category = str_replace('-', '', $fcontents[4]);
        
        // The post link.
        $post_link = $blog_url.'/'.str_replace(array(FILE_EXT, POSTS_DIR), '', $filename);
        
        // The post image.
        $image = str_replace(array(FILE_EXT), '', $filename).'.jpg';
        
        if (file_exists($image)) {
            $post_image = $blog_url.'/'.str_replace(array(FILE_EXT, '../'), '', $filename).'.jpg';
        } else {
            $post_image = 'https://api.twitter.com/1/users/profile_image?screen_name='.$post_author_twitter.'&size=bigger';
        }
        
        // The post.
        $post = Markdown(join('', $fcontents));
        
        // Get the post template file.
        include $post_file;
        
        // Cache the post on if caching is turned on.
        if ($post_cache != 'off')
        {
            $fp = fopen($cachefile, 'w'); 
            fwrite($fp, ob_get_contents()); 
            fclose($fp);
        }
        
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
    $dir = basename($_SERVER['REQUEST_URI']);
    $url = $protocol . '://' . $host . '/' . $dir;
    
    ?>
    
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>Let's Get Started</title>
            <link rel="stylesheet" href="./dropplets/style/style.css" />
            <link rel="shortcut icon" href="./dropplets/style/images/favicon.png">
        </head>
        
        <body>
            <img src="./dropplets/style/images/logo.png" alt="Dropplets" />
            
            <h1>Let's Get Started</h1>
            <p>With Dropplets, there's no database or confusing admins to worry about, just simple markdown blogging goodness. To get started, enter your site information below (all fields are required) and then click the check mark at the bottom. That's all there's to it :)</p>
            
    		<form method="POST" action="./dropplets/config/submit-settings.php">
    		    <fieldset>
    		        <div class="input">
    		            <input type="text" name="blog_email" id="blog_email" required placeholder="The Email Address for Your Blog">
    		        </div> 
    		        
    		        <div class="input">
    		            <input type="text" name="blog_twitter" id="blog_twitter" required placeholder="The Twitter ID for Your Blog (e.g. &quot;dropplets&quot;)">
    		        </div> 
    		    </fieldset>
    		    
    		    <fieldset>
        		    <div class="input hidden">
        		        <input type="text" name="blog_url" id="blog_url" required readonly value="<?php echo($url) ?>">
        		    </div>
        		    
        		    <div class="input">
        		        <input type="text" name="blog_title" id="blog_title" required placeholder="Your Blog Title">
        		    </div>
        		    
        		    <div class="input">
        		        <textarea name="meta_description" id="meta_description" rows="6" required placeholder="Add your site description here... just a short sentence that describes what your blog is going to be about."></textarea>
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
        		    <div class="input">
        		        <input type="password" name="password" id="password" required placeholder="Enter a Password">
        		    </div> 
    		    </fieldset>
    		    
    		    <fieldset class="hidden">
    		        <div class="input">
    		            <input type="text" name="template" id="template" required value="simple">
    		        </div>
    		    </fieldset>
    		    
    		    <button type="submit" name="submit" value="submit"></button>
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
                $title = Markdown($fcontents[0]);
                
                // The post author.
                $post_author = str_replace('-', '', $fcontents[1]);
                
                // The post author Twitter account.
                $post_author_twitter = str_replace('- ', '', $fcontents[2]);
                                
                // The published date.
                $time = str_replace('-', '', $fcontents[3]);
                
                // The post category.
                $category = str_replace('-', '', $fcontents[4]);
                
                // The post status.
                $post_status = str_replace('- ', '', $fcontents[5]);
                
                // The post intro.
                $intro = Markdown($fcontents[7]);
                
                $files[] = array('fname' => $entry, 'title' => $title, 'post_author' => $post_author, 'post_author_twitter' => $post_author_twitter, 'time' => $time, 'category' => $category, 'post_status' => $post_status, 'intro' => $intro);
                $filetimes[] = $time;
                $titles[] = $title;
                $post_authors[] = $post_author;
                $post_authors_twitter[] = $post_author_twitter;
                $categories[] = $category;
                $post_statuses[] = $post_status;
                $introductions[] = $intro;
            }
        }
        array_multisort($filetimes, SORT_DESC, $files);
        return $files;
        
    } else {
        return false;
    }
}