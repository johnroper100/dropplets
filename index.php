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
$index_cache = 'off';

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

$pagination_on_off = "on";  //Infinite scroll by default?
$posts_per_page = 4;
$infinite_scroll = "on"; //Infinite scroll works only if pagination is on.
$post_directory = './posts/';
$cache_directory = './posts/cache/';

if (glob($post_directory . '*.md') != false)
{
    $posts_dir = './posts/';
} else {
    $posts_dir = './dropplets/welcome/';
}

define('POSTS_DIR', $posts_dir);
define('FILE_EXT', '.md');

/*-----------------------------------------------------------------------------------*/
/* Cache Configuration
/*-----------------------------------------------------------------------------------*/

define('CACHE_DIR', $cache_directory);

if (!file_exists(CACHE_DIR) && ($post_cache != 'off' || $index_cache != 'off')) {
	mkdir(CACHE_DIR,0755,TRUE);
}

/*-----------------------------------------------------------------------------------*/
/* Template Files
/*-----------------------------------------------------------------------------------*/

$template_dir = './templates/' . $template . '/';
$template_dir_url = $blog_url . '/templates/' . $template . '/';

// define the default locations of the template files.
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
foreach(glob('./dropplets/plugins/' . '*.php') as $plugin){
    include_once $plugin;
}

// Reading file names.
if (empty($_GET['filename'])) {
    $filename = NULL;
} else if($_GET['filename'] == 'rss' || $_GET['filename'] == 'atom') {
    $filename = $_GET['filename'];
} else {

    //Filename can be /some/blog/post-filename.md We should get the last part only.
    $filename = explode('/',$_GET['filename']);
    $filename = POSTS_DIR . $filename[count($filename) - 1] . FILE_EXT;
}

/*-----------------------------------------------------------------------------------*/
/* The Home Page (All Posts)
/*-----------------------------------------------------------------------------------*/

if ($filename==NULL) {

    $page = (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 1) ? $_GET['page'] : 1;
    $offset = ($page == 1) ? 0 : ($page - 1) * $posts_per_page;

    //Index page cache file name, will be used if index_cache = "on"
    $cachefile = CACHE_DIR . "index" .$page. '.html';
    
    //If index cache file exists, serve it directly wihout getting all posts    
    if (file_exists($cachefile) && $index_cache != 'off') {
    
        // Get the cached post.
        include $cachefile;
        exit;
        
    // If there is a file for the selected permalink, display and cache the post.
    } 
    
    $all_posts = get_all_posts();
    $pagination = ($pagination_on_off != "off") ? get_pagination($page,round(count($all_posts)/ $posts_per_page)) : "";
    $posts = ($pagination_on_off != "off") ? array_slice($all_posts,$offset,($posts_per_page > 0) ? $posts_per_page : null) : $all_posts;

    if($posts) {
        ob_start();
        $content = '';
        foreach($posts as $post) {
            
            // Get the post title.
            $post_title = str_replace(array('<h1>','</h1>'), '', $post['post_title']);
            
            // Get the post author.
            $post_author = $post['post_author'];
            
            // Get the post author twitter id.
            $post_author_twitter = $post['post_author_twitter'];
            
            // Get the published ISO date.
            $published_iso_date = $post['post_date'];
                        
            // Generate the published date.
            $published_date = date_format(date_create($published_iso_date), $date_format);
            
            // Get the post category.
            $post_category = $post['post_category'];
            
            // Get the post status.
            $post_status = $post['post_status'];
            
            // Get the post intro.
            $post_intro = $post['post_intro'];
            
            // Get the post link.
            $post_link = str_replace(FILE_EXT, '', $post['fname']);
            
            // Get the post image url.
            $image = str_replace(array(FILE_EXT), '', POSTS_DIR.$post['fname']).'.jpg';
            
            if (file_exists($image)) {
                $post_image = $blog_url.'/'.str_replace(array(FILE_EXT, '../'), '', POSTS_DIR.$post['fname']).'.jpg';
            } else {
                $post_image = 'https://api.twitter.com/1/users/profile_image?screen_name='.$post_author_twitter.'&size=bigger';
            }
            
            // Get the site intro template file.
            include_once $intro_file;
            
            // Get the milti-post template file.
            include $posts_file;
        }
        echo $content;
        $content = ob_get_contents();
        
        // Get the site title
        $page_title = $blog_title;
        
        $blog_image = 'https://api.twitter.com/1/users/profile_image?screen_name='.$blog_twitter.'&size=bigger';
        
        // Get the page description and author meta.
        $get_page_meta[] = '<meta name="description" content="' . $meta_description . '">';
        $get_page_meta[] = '<meta name="author" content="' . $blog_title . '">';
        
        // Get the Twitter card.
        $get_page_meta[] = '<meta name="twitter:card" content="summary">';
        $get_page_meta[] = '<meta name="twitter:site" content="' . $blog_twitter . '">';
        $get_page_meta[] = '<meta name="twitter:title" content="' . $blog_title . '">';
        $get_page_meta[] = '<meta name="twitter:description" content="' . $meta_description . '">';
        $get_page_meta[] = '<meta name="twitter:creator" content="' . $blog_twitter . '">';
        $get_page_meta[] = '<meta name="twitter:image:src" content="' . $blog_image . '">';
        $get_page_meta[] = '<meta name="twitter:domain" content="' . $blog_url . '">';
        
        // Get the Open Graph tags.
        $get_page_meta[] = '<meta property="og:type" content="website">';
        $get_page_meta[] = '<meta property="og:title" content="' . $blog_title . '">';
        $get_page_meta[] = '<meta property="og:site_name" content="' . $blog_title . '">';
        $get_page_meta[] = '<meta property="og:url" content="' .$blog_url . '">';
        $get_page_meta[] = '<meta property="og:description" content="' . $meta_description . '">';
        $get_page_meta[] = '<meta property="og:image" content="' . $blog_image . '">';
        
        // Get all page meta.
        $page_meta = implode("\n", $get_page_meta);

        ob_end_clean();
    } else {
        ob_start();

        // Define the site title.
        $page_title = $error_title;
        $page_meta = '';
        
        // Get the 404 page template.
        include $not_found_file;

        //Get the contents
        $content = ob_get_contents();

        //Flush the buffer so that we dont get the page 2x times
        ob_end_clean();
    }
        ob_start();
        
        // Get the index template file.
        include_once $index_file;
        
        //Now that we have the whole index page generated, put it in cache folder
        if ($index_cache != 'off') {
            $fp = fopen($cachefile, 'w');
            fwrite($fp, ob_get_contents());
            fclose($fp);
        }
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
                
                // Remove HTML from the RSS feed.
                $item->setTitle(substr($post['post_title'], 4, -6));
                $item->setLink(rtrim($blog_url, '/').'/'.str_replace(FILE_EXT, '', $post['fname']));
                $item->setDate($post['post_date']);
                
                // Remove Meta from the RSS feed.
				$remove_metadata_from = file(rtrim(POSTS_DIR, '/').'/'.$post['fname']);
				
                if($filename=='rss') {
                    $item->addElement('author', str_replace('-', '', $remove_metadata_from[1]).' - ' . $blog_email);
                    $item->addElement('guid', rtrim($blog_url, '/').'/'.str_replace(FILE_EXT, '', $post['fname']));
                }
                
				// Remove the metadata from the RSS feed.
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
    
    // Define the post file.
    $fcontents = file($filename);
    $slug_array = explode("/", $filename);
    $slug_len = count($slug_array);
    
    // This was hardcoded array index, it should always return the last index.
    $slug = str_replace(array(FILE_EXT), '', $slug_array[$slug_len - 1]);

    // Define the cached file.
    $cachefile = CACHE_DIR.$slug.'.html';
    
    // If there's no file for the selected permalink, grab the 404 page template.
    if (!file_exists($filename)) {
    
        //Change the cache file to 404 page.
        $cachefile = CACHE_DIR.'404.html';
        
        // Define the site title.
        $page_title = $error_title;
    
        // Get the 404 page template.
        include $not_found_file;
        
        // Get the contents.
        $content = ob_get_contents();
        
        // Flush the buffer so that we dont get the page 2x times.
        ob_end_clean();
        
        // Start new buffer.
        ob_start(); 
        
	    // Get the index template file.
        include_once $index_file;
        
        // Cache the post on if caching is turned on.
        if ($post_cache != 'off')
        {
            $fp = fopen($cachefile, 'w');
            fwrite($fp, ob_get_contents());
            fclose($fp);
        }

    // If there is a cached file for the selected permalink, display the cached post.  
    } else if (file_exists($cachefile)) {
    
        // Define site title
        $page_title = str_replace('# ', '', $fcontents[0]);
        
        // Get the cached post.
        include $cachefile;
        
        exit;
        
    // If there is a file for the selected permalink, display and cache the post.
    } else {
        
        // Get the post title.
        $post_title = str_replace('# ', '', $fcontents[0]);
        
        // Get the post title.
        $post_intro = htmlspecialchars($fcontents[7]);
        
        // Get the post author.
        $post_author = str_replace('-', '', $fcontents[1]);
        
        // Get the post author Twitter ID.
        $post_author_twitter = str_replace('- ', '', $fcontents[2]);
        
        // Get the published date.
        $published_iso_date = str_replace('-', '', $fcontents[3]);
                
        // Generate the published date.
        $published_date = date_format(date_create($published_iso_date), $date_format);
        
        // Get the post category.
        $post_category = str_replace('-', '', $fcontents[4]);
        
        // Get the post link.
        $post_link = $blog_url.'/'.str_replace(array(FILE_EXT, POSTS_DIR), '', $filename);
        
        // Get the post image url.
        $image = str_replace(array(FILE_EXT), '', $filename).'.jpg';
        
        if (file_exists($image)) {
            $post_image = $blog_url.'/'.str_replace(array(FILE_EXT, '../'), '', $filename).'.jpg';
        } else {
            $post_image = 'https://api.twitter.com/1/users/profile_image?screen_name='.$post_author_twitter.'&size=bigger';
        }
        
        // Get the site title.
        $page_title = str_replace('# ', '', $fcontents[0]);
        
        // Generate the page description and author meta.
        $get_page_meta[] = '<meta name="description" content="' . $post_intro . '">';
        $get_page_meta[] = '<meta name="author" content="' . $post_author . '">';
        
        // Generate the Twitter card.
        $get_page_meta[] = '<meta name="twitter:card" content="summary">';
        $get_page_meta[] = '<meta name="twitter:site" content="' . $blog_twitter . '">';
        $get_page_meta[] = '<meta name="twitter:title" content="' . $page_title . '">';
        $get_page_meta[] = '<meta name="twitter:description" content="' . $post_intro  . '">';
        $get_page_meta[] = '<meta name="twitter:creator" content="' . $post_author_twitter . '">';
        $get_page_meta[] = '<meta name="twitter:image:src" content="' . $post_image . '">';
        $get_page_meta[] = '<meta name="twitter:domain" content="' . $post_link . '">';
        
        // Get the Open Graph tags.
        $get_page_meta[] = '<meta property="og:type" content="article">';
        $get_page_meta[] = '<meta property="og:title" content="' . $page_title . '">';
        $get_page_meta[] = '<meta property="og:site_name" content="' . $page_title . '">';
        $get_page_meta[] = '<meta property="og:url" content="' . $post_link . '">';
        $get_page_meta[] = '<meta property="og:description" content="' . $post_intro . '">';
        $get_page_meta[] = '<meta property="og:image" content="' . $post_image . '">';
        
        // Generate all page meta.
        $page_meta = implode("\n", $get_page_meta);
        
        // Generate the post.
        $post = Markdown(join('', $fcontents));
        
        // Get the post template file.
        include $post_file;

        $content = ob_get_contents();
        ob_end_clean();
        ob_start();
        
        // Get the index template file.
        include_once $index_file;
        
        // Cache the post on if caching is turned on.
        if ($post_cache != 'off')
        {
            $fp = fopen($cachefile, 'w');
            fwrite($fp, ob_get_contents());
            fclose($fp);
        }
    }
}

/*-----------------------------------------------------------------------------------*/
/* Run Setup if No Config
/*-----------------------------------------------------------------------------------*/

} else {
    
    // Fetch the current url.
    $protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
    $host = $_SERVER['HTTP_HOST'];
    
    // Subdirectory support.
    $dir      = dirname($_SERVER['REQUEST_URI']) . basename($_SERVER['REQUEST_URI']);
    $url      = $protocol . '://' . $host . $dir;
    $is_writable = (TRUE == is_writable(dirname(__FILE__) . '/dropplets/config/'));
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
            <?php if (!$is_writable) { ?>
                <p style="color:red;">It seems that your config folder is not writable, please add the necessary permissions.</p>
            <?php } ?>
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
        		    
        		    <div class="input">
        		        <input type="password" name="password-confirmation" id="password-confirmation" required placeholder="Confirm Your Password" onblur="confirmPass()">
        		    </div> 
    		    </fieldset>
    		    
    		    <fieldset class="hidden">
    		        <div class="input">
    		            <input type="text" name="template" id="template" required value="simple">
    		        </div>
    		    </fieldset>
    		    
    		    <button type="submit" name="submit" value="submit"></button>
    		</form>
    		
            <script>
            	function confirmPass() {
            		var pass = document.getElementById("password").value
            		var confPass = document.getElementById("password-confirmation").value
            		if(pass != confPass) {
            			alert('Your passwords do not match!');
            		}
            	}
            </script>
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
                
                // Define the post file.
                $fcontents = file(POSTS_DIR.$entry);
                
                // Define the post title.
                $post_title = Markdown($fcontents[0]);
                
                // Define the post author.
                $post_author = str_replace('-', '', $fcontents[1]);
                
                // Define the post author Twitter account.
                $post_author_twitter = str_replace('- ', '', $fcontents[2]);
                                
                // Define the published date.
                $post_date = str_replace('-', '', $fcontents[3]);
                
                // Define the post category.
                $post_category = str_replace('-', '', $fcontents[4]);
                
                // Define the post status.
                $post_status = str_replace('- ', '', $fcontents[5]);
                
                // Define the post intro.
                $post_intro = Markdown($fcontents[7]);
                
                // Pull everything together for the loop.
                $files[] = array('fname' => $entry, 'post_title' => $post_title, 'post_author' => $post_author, 'post_author_twitter' => $post_author_twitter, 'post_date' => $post_date, 'post_category' => $post_category, 'post_status' => $post_status, 'post_intro' => $post_intro);
                $post_dates[] = $post_date;
                $post_titles[] = $post_title;
                $post_authors[] = $post_author;
                $post_authors_twitter[] = $post_author_twitter;
                $post_categories[] = $post_category;
                $post_statuses[] = $post_status;
                $post_intros[] = $post_intro;
            }
        }
        array_multisort($post_dates, SORT_DESC, $files);
        return $files;
        
    } else {
        return false;
    }
}

function get_pagination($page,$total) {
    $string = '';
    $string .= "<ul style=\"list-style:none; width:400px; margin:15px auto;\">";

    for ($i = 1; $i<=$total;$i++) {
        if ($i == $page) {
            $string .= "<li style='display: inline-block; margin:5px;' class=\"active\"><a class=\"button\" href='#'>".$i."</a></li>";
        } else {
            $string .=  "<li style='display: inline-block; margin:5px;'><a class=\"button\" href=\"/?page=".$i."\">".$i."</a></li>";
        }
    }
    $string .= "</ul>";
    return $string;
}
