<?php

/*-----------------------------------------------------------------------------------*/
/* Configuration & Options
/*-----------------------------------------------------------------------------------*/

$config = json_decode(file_get_contents('./config.json'));
$site = $config->site;
$dropplets = $config->dropplets;
$feeds = $config->feeds;
$intro = $config->site->intro;
$date_format = $site->date_format;
$error = $config->site->error;

/*-----------------------------------------------------------------------------------*/
/* Template Files
/*-----------------------------------------------------------------------------------*/

$index_file = './templates/index.php';
$intro_file = './templates/intro.php';
$post_file = './templates/post.php';
$posts_file = './templates/posts.php';
$not_found_file = './templates/404.php';

/*-----------------------------------------------------------------------------------*/
/* Let's Get Started
/*-----------------------------------------------------------------------------------*/

// Display errors if there are any.
ini_set('display_errors', $dropplets->display_errors);

// Get required plugins.
include_once './plugins/feedwriter.php';
include_once './plugins/markdown.php';

// Reading file names.
if (empty($_GET['filename'])) {
    $filename = NULL;
} else if($_GET['filename'] == 'rss' || $_GET['filename'] == "atom") {
    $filename = $_GET['filename'];
} else {
    $filename = $dropplets->directory_of_posts . $_GET['filename'] . $dropplets->post_file_extension;
}

/*-----------------------------------------------------------------------------------*/
/* The Home Page
/*-----------------------------------------------------------------------------------*/

if ($filename==NULL) {
    $posts = get_all_posts();
    if($posts) {
        ob_start();
        $content = "";
        foreach($posts as $post) {
        
            // The site title
            $site_title = $site->title;
            
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
            $post_link = str_replace($dropplets->post_file_extension, '', $post['fname']);
            
            // The post thumbnail.
            $post_thumbnail = $site->url.'/'.str_replace(array($dropplets->post_file_extension, '../'), "", $dropplets->directory_of_posts.$post['fname']).".jpg";
            
            // Grab the site intro template file.
            include_once $intro_file;
            
            // Grab the milti-post template file.
            include $posts_file;   
        }
        echo Markdown($content);
        $content = ob_get_contents();
        ob_end_clean();
    } else {
        ob_start();
        
        // The site title
        $site_title = $error->error_title;
        
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

else if ($filename == "rss" || $filename == "atom") {
    ($filename=="rss") ? $feed = new FeedWriter(RSS2) : $feed = new FeedWriter(ATOM);

    $feed->setTitle($site->title);
    $feed->setLink($site->url);

    if($filename=="rss") {
        $feed->setDescription($site->meta_description);
        $feed->setChannelElement('language', $feeds->language);
        $feed->setChannelElement('pubDate', date(DATE_RSS, time()));
    } else {
        $feed->setChannelElement('author', $site->author->name." - " . $site->author->email);
        $feed->setChannelElement('updated', date(DATE_RSS, time()));
    }

    $posts = get_all_posts();

    if($posts) {
        $c=0;
        foreach($posts as $post) {
            if($c<$feeds->max_items) {
                $item = $feed->createNewItem();
                $item->setTitle($post['title']);
                $item->setLink(rtrim($site->url, '/').'/'.str_replace($dropplets->post_file_extension, "", $post['fname']));
                $item->setDate($post['time']);
                $item->setDescription(Markdown(file_get_contents(rtrim($dropplets->directory_of_posts, '/').'/'.$post['fname'])));
                if($filename=="rss") {
                    $item->addElement('author', $site->author->name." - " . $site->author->email);
                    $item->addElement('guid', rtrim($site->url, '/').'/'.str_replace($dropplets->post_file_extension, "", $post['fname']));
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
    
    // If there's no file for the selected permalink, grab the 404 page template.
    if (!file_exists($filename)) {
    
        // The site title
        $site_title = $error->error_title;
    
        // Get the 404 page template.
        include $not_found_file;
    
    // If there is a file for the selected permalink, display the post.  
    } else {
    
        // The post file.
        $fcontents = file($filename);
        
        // The site title
        $site_title = str_replace("# ", "", $fcontents[0]);
        
        // The post title.
        $post_title = str_replace("# ", "", $fcontents[0]);
        
        // The published date.
        $published_iso_date = str_replace("-", "", $fcontents[1]);
                
        // The published date.
        $published_date = date_format(date_create($published_iso_date), $date_format);
        
        // The post category.
        $post_category = str_replace("-", "", $fcontents[2]);
        
        // The post link.
        $post_link = $site->url.'/'.str_replace(array($dropplets->post_file_extension, $dropplets->directory_of_posts), "", $filename);
        
        // The post thumbnail.
        $post_thumbnail = $site->url.'/'.str_replace(array($dropplets->post_file_extension, '../'), "", $filename).".jpg";
        
        // The post.
        $post = Markdown(join('', $fcontents));
        
        // Get the post template file.
        include $post_file;
    }
    $content = ob_get_contents();
    ob_end_clean();
    
    // Get the index template file.
    include_once $index_file;
}

/*-----------------------------------------------------------------------------------*/
/* Get All Posts Function (Used For the Home Page Above)
/*-----------------------------------------------------------------------------------*/

function get_all_posts() {
    global $dropplets;
    
    if($handle = opendir($dropplets->directory_of_posts)) {
        
        $files = array();
        $filetimes = array();
        
        while (false !== ($entry = readdir($handle))) {
            if(substr(strrchr($entry,'.'),1)==ltrim($dropplets->post_file_extension, '.')) {
                
                // The post file.
                $fcontents = file($dropplets->directory_of_posts.$entry);
                
                // The post title.
                $title = str_replace("# ", "", $fcontents[0]);
                                
                // The published date.
                $time = str_replace("-", "", $fcontents[1]);
                
                // The post category.
                $category = str_replace("-", "", $fcontents[2]);
                
                // The post intro.
                $intro = $fcontents[4];
                
                $files[] = array("time" => $time, "fname" => $entry, "title" => $title, "category" => $category, "intro" => $intro);
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
