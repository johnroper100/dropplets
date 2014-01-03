<?php

/*-----------------------------------------------------------------------------------*/
/* Include 3rd Party Functions
/*-----------------------------------------------------------------------------------*/

include('./dropplets/includes/feedwriter.php');
include('./dropplets/includes/markdown.php');
include('./dropplets/includes/phpass.php');
include('./dropplets/includes/actions.php');

/*-----------------------------------------------------------------------------------*/
/* Include All Plugins in Plugins Directory
/*-----------------------------------------------------------------------------------*/

foreach(glob('./plugins/' . '*.php') as $plugin){
    include_once $plugin;
}

/*-----------------------------------------------------------------------------------*/
/* User Machine
/*-----------------------------------------------------------------------------------*/

// Password hashing via phpass.
$hasher  = new PasswordHash(8,FALSE);

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
            if (!isset($_GET["verify"])) {
            
                $code = sha1(md5(rand()));

                $verify_file_contents[] = "<?php\n";
                $verify_file_contents[] = "\$verification_code = \"" . $code . "\";";
                file_put_contents($verification_file, implode("\n", $verify_file_contents));

                $recovery_url = sprintf("%s/index.php?action=forgot&verify=%s,", $blog_url, $code);
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
            
            header('Location: ' . './');
            break;
    }
    
}
// not show warning or error if the element is not set
if (!(defined('LOGIN_ERROR'))) { 
	if (!isset($login_error)) { $login_error=''; } 
	define('LOGIN_ERROR', $login_error);
}


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

                // Define the post category.
                $post_category = str_replace(array("\n", '-'), '', $fcontents[4]);

                // Early return if we only want posts from a certain category
				if (isset($options["category"])) { // not show warning or error if the element is not set
					if($options["category"] && $options["category"] != trim(strtolower($post_category))) {
						continue;
					}
				}

                // Define the post status.
                $post_status = str_replace(array("\n", '- '), '', $fcontents[5]);

                // Define the post intro.
                $post_intro = Markdown($fcontents[7]);

                // Define the post content
				$post_content = Markdown(join('', array_slice($fcontents, 6, sizeof($fcontents) -1))); // change $fcontents.length to sizeof($fcontents)
                
				// Pull everything together for the loop.
                $files[] = array('fname' => $entry, 'post_title' => $post_title, 'post_author' => $post_author, 'post_author_twitter' => $post_author_twitter, 'post_date' => $post_date, 'post_category' => $post_category, 'post_status' => $post_status, 'post_intro' => $post_intro, 'post_content' => $post_content);
                $post_dates[] = $post_date;
                $post_titles[] = $post_title;
                $post_authors[] = $post_author;
                $post_authors_twitter[] = $post_author_twitter;
                $post_categories[] = $post_category;
                $post_statuses[] = $post_status;
                $post_intros[] = $post_intro;
                $post_contents[] = $post_content;
            }
        }
		if (count($files)>1) {
			array_multisort($post_dates, SORT_DESC, $files);
			return $files;
		} else {
			return false;
		}

    } else {
        return false;
    }
}
/*-----------------------------------------------------------------------------------*/
/* Get all categories for make menu
/*-----------------------------------------------------------------------------------*/
function get_Menu() {
	$menu  = '<script type="text/javascript">';
	$menu .= "function OpenWin(w, h, qUrl) {window.open(qUrl, 'Window', 'width=' + w + ',height=' + h + ',location=yes,personalbar=no,menubar=no,resizable=no,status=no,scrollbars=no,toolbar=no'); return false;}";
	$menu .= "function changeMenu(){ $('#mnu').toggle(); $('#btnMenu').toggle(); }";
  	$menu .= '</script>';  
  		$file = BLOG_PATH . "templates/" . ACTIVE_TEMPLATE . "/menu.css";
    	if(file_exists($file)) {   
            $menu .= '<link rel="stylesheet" href="' . BLOG_URL . 'templates/' . ACTIVE_TEMPLATE . '/menu.css" type="text/css">';
		} else {
			$menu .= '<link rel="stylesheet" href="' . BLOG_URL . 'src/css/menu.css" type="text/css">';
		}	 
	$menu .= "<div id='btnMenu'><button onclick='javascript:changeMenu()' class='myButton'><i class='fa fa-list'></i></button></div>";	
	$menu .= "<div class='mnuright' id='mnu' style='display:none;'>";
	$menu .= "<span><button onclick='javascript:changeMenu()' class='myButton'><i class='fa fa-list'></i></button></span>";	
	$menu .= "<ul>";
    if($handle = opendir(POSTS_DIR)) {
        $post_categories = array();
        while (false !== ($entry = readdir($handle))) {
            if(substr(strrchr($entry,'.'),1)==ltrim(FILE_EXT, '.')) {
                // Define the post file.
                $fcontents = file(POSTS_DIR.$entry);
                // Define the post category.
                $post_category = str_replace(array("\n", '-'), '', $fcontents[4]);
                
				// Pull everything together for the loop.
				//if(isset($post_categories['say']) && $post_categories['say'] == $post_category) {
				if (in_array($post_category, $post_categories)){
					// value is in array - nothing to do
				} else {
					$post_categories[] = $post_category;
				}
            }
        }
		if (count($post_categories)>1) {
			asort($post_categories);
			foreach($post_categories as $lnk)
			{
				$menu .= "<li class='menu'><a href='" . BLOG_URL . "category/" . str_replace(' ','+',trim($lnk)) . "'>" . trim($lnk) . "</a></li>";
			}
		}
    }
	$menu  .= "</ul>";
	$menu  .= "<span style='text-align:center;'>";
	$menu .= "<button onclick=\"window.location.href='" . BLOG_URL . "';\" class='myButton'><i class='fa fa-map-marker'></i></button>&nbsp;";
	$menu .= "<button onclick=\"window.location.href='mailto:" . BLOG_EMAIL . "?subject=Contact from " . BLOG_TITLE . "';\" class='myButton'><i class='fa fa-envelope-o fa-2'></i></button>&nbsp;";
	$menu  .= "<button onclick=\"javascript:OpenWin('600', '250','https://twitter.com/intent/tweet?text=" . BLOG_TITLE . "%20>%20" . BLOG_URL . "')\" title='" . _t('Comment on') . " Twitter' class='myButton'><i class='fa fa-comment-o'></i></button></span>";
	$menu  .= "</div>";	
	return $menu;
}

/*-----------------------------------------------------------------------------------*/
/* Get Posts for Selected Category
/*-----------------------------------------------------------------------------------*/

function get_posts_for_category($category) {
    $category = trim(strtolower($category));
    return get_all_posts(array("category" => $category));
}

/*-----------------------------------------------------------------------------------*/
/*-----------------------------------------------------------------------------------*/
/* Get Image for a Post
/*-----------------------------------------------------------------------------------*/
function get_post_image_url($filename)
{
    global $blog_url;
    $supportedFormats = array( "jpg", "png", "gif" );
    $slug = pathinfo($filename, PATHINFO_FILENAME);

    foreach($supportedFormats as $fmt)
    {
        $imgFile = sprintf("%s%s.%s", POSTS_DIR, $slug, $fmt);
        if (file_exists($imgFile))
            return sprintf("%s/%s.%s", "${blog_url}posts", $slug, $fmt);
    }

    return false;
}
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
/* Get Premium Templates
/*-----------------------------------------------------------------------------------*/

function get_premium_templates($type = 'all', $target = 'blank') {
    
    $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');
    
    if($templates===FALSE) {
        // Feed not available.
    } else {
        foreach ($templates as $template):
            
            // Define some variables
            $template_file_name=$template->file;
            $template_price=$template->price;
            $template_url=$template->url;
            
            { ?>
            <li class="premium">
                <img src="http://dropplets.com/demo/templates/<?php echo $template_file_name; ?>/screenshot.jpg">
                <a class="buy" href="http://gum.co/dp-<?php echo $template_file_name; ?>" title="Purchase/Download"><?php echo $template_price; ?></a> 
                <a class="preview" href="http://dropplets.com/demo/?template=<?php echo $template_file_name; ?>" title="Prview" target="_<?php echo $target; ?>">p</a>    
            </li>
            <?php } 
        endforeach;
    }
}

function count_premium_templates($type = 'all') {

    $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');

    if($templates===FALSE) {
        // Feed not available.
    } else {
        $templates = simplexml_load_file('http://dropplets.com/templates-'. $type .'.xml');
        $templates_count = $templates->children();
        echo count($templates_count);
    }
}

/*-----------------------------------------------------------------------------------*/
/* get current Home url
/*-----------------------------------------------------------------------------------*/
function get_HOME(){
    // Get the components of the current url.
    $protocol = @( $_SERVER["HTTPS"] != 'on') ? 'http://' : 'https://';
    $domain = $_SERVER["SERVER_NAME"];
    $port = $_SERVER["SERVER_PORT"];
    $path = $_SERVER["REQUEST_URI"];
    // Check if running on alternate port.
    if ($protocol === "https://") {
        if ($port === 443)
            $currentpage = $protocol . $domain;
        else
            $currentpage = $protocol . $domain . ":" . $port;
    } elseif ($protocol === "http://") {
        if ($port === 80)
            $currentpage = $protocol . $domain;
        else
            $currentpage = $protocol . $domain . ":" . $port;
    }
    $currentpage .= $path;
	return $currentpage;	
}
/*-----------------------------------------------------------------------------------*/
/* If is Home (Could use "is_single", "is_category" as well.)
/*-----------------------------------------------------------------------------------*/

$homepage = BLOG_URL;

// Get the current page.    
// $currentpage  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["SERVER_NAME"] : 'https://'.$_SERVER["SERVER_NAME"];
//$currentpage .= $_SERVER["REQUEST_URI"];
$currentpage  = get_HOME();

// If is home. 
$is_home = ($homepage==$currentpage);
define('IS_HOME', $is_home);
define('IS_CATEGORY', (bool)strstr($_SERVER['REQUEST_URI'], '/category/'));
define('IS_SINGLE', !(IS_HOME || IS_CATEGORY));

/*-----------------------------------------------------------------------------------*/
/* Get Profile Image
/*-----------------------------------------------------------------------------------*/
function get_myProfile_img($qImg,$qId){
	if (trim($qImg)=='') { // need name
		return '';
	}
	if (trim($qId)=='') { // need id
		return '';
	}	
	try {	
		if ($qId == null) {
			return '';
		}
		$cache_image = '';
		// Set the cached profile image.
		switch($qImg) {
			case "gravatar":
					$cache_image = './cache/gravatar.jpg';
					$image_url ="http://www.gravatar.com/avatar/" . md5(strtolower(trim($qId))) . "&s=100";
					// to save image use this:
					//$grav_url = "http://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?d=" . urlencode($default) . "&s=" . $size;
				break;
			case "facebook":
					$cache_image = './cache/facebook_'.$qId.'.jpg';
					$image_url =  'https://graph.facebook.com/'.$qId.'/picture';			
				break;
			case "dtwitter":
					$cache_image = './cache/twitter_d_'.$qId.'.jpg';
					$image_url = 'http://dropplets.com/profiles/?id='.$qId.'';					
				break;		
			case "twitter":
					return get_twitter_profile_imgV2($qId);					
				break;	
			case "tumblr":			
				$cache_image = './cache/tumblr_' . $qId . '.png';
				$image_url ="http://api.tumblr.com/v2/blog/" . $qId . ".tumblr.com/avatar";
				break;
			case "google":
					$cache_image = './cache/gplus_'.$qId.'.jpg';
					$image_url = 'https://profiles.google.com/s2/photos/profile/'.$qId;
				break;
		}

		if (!file_exists($cache_image)) {
			$image = @file_get_contents($image_url);
			if (($image == "")||($image === FALSE)||(stripos($image,'404')>0)||(stripos($image,'status')>0)||(stripos($image,'meta')>0)||(stripos($image,'response')>0)) {
				return '';
			} else {
				// Cache the image if it doesn't already exist.
				file_put_contents($cache_image, $image);
			} 
		}
	} catch (Exception $e) {
		$cache_image = '';
	}
	return $cache_image;
}
function get_gravatar_profile_img($gvImg){
	return get_myProfile_img("gravatar",$gvImg);
}
function get_tumblr_profile_img($tbImg){
	return get_myProfile_img("tumblr",$tbImg);
}
function get_facebook_profile_img($fImg) {
	return get_myProfile_img("facebook",$fImg);
}
function get_gplus_profile_img($gImg) {
	return get_myProfile_img("google",$gImg);
}
function get_twitter_profile_img($tImg) {
	return get_myProfile_img("dtwitter",$tImg);
}

function get_twitter_profile_imgV2($tImg) { // use new Twitter API
//As you rightly pointed out, as of June 11th 2013 you can't make unauthenticated requests, 
//or any to the 1.0 API any more, because it has been retired. 
//So OAuth is the way to make requests to the 1.1 API.
//https://dev.twitter.com/apps
//https://dev.twitter.com/docs/api/1.1/get/users/lookup
// The link below, get read-only access
// Give your application READ access, and hit "Update" at the bottom.
// http://stackoverflow.com/questions/12916539/simplest-php-example-for-retrieving-user-timeline-with-twitter-api-version-1-1/15314662#15314662
	if (trim($tImg)=='') {
		return '';
	}	
	try {
		$twitter_image = '';
		if (!file_exists('./cache/twitter_'.$tImg.'.jpg')) {
			if ((TWITTER_TOKEN != "") || (TWITTER_TOKEN != "") || (TWITTER_TOKEN != "") || (TWITTER_TOKEN != "")) {
				$settings = array(
					'oauth_access_token' => TWITTER_TOKEN,
					'oauth_access_token_secret' => TWITTER_TSECRET,
					'consumer_key' => TWITTER_CKEY,
					'consumer_secret' => TWITTER_CSECRET
				);	
				/** URL for REST request, see: https://dev.twitter.com/docs/api/1.1/ **/
				$api_call = 'https://api.twitter.com/1.1/users/show.json';
				/** Perform a GET request and echo the response **/
				/** Note: Set the GET field BEFORE calling buildOauth(); **/
				$getfield = '?screen_name='.$tImg;
				$requestMethod = 'GET';
				/** Perform a POST request and echo the response **/
				$twitter = new TwitterAPIExchange($settings);
				$results = $twitter->setGetfield($getfield)->buildOauth($api_call,$requestMethod)->performRequest();			
				if (isset($results)) {
					$results = json_decode($results);
					if (isset($results)) {
						// Cache the image if it doesn't already exist.					
						$image = str_replace('_normal', '100', $results->profile_image_url);
						file_put_contents('./cache/twitter_'.$tImg.'.jpg', $image);
						// Set the cached profile image.
						$twitter_image =  './cache/twitter_'.$tImg.'.jpg';						
					}
				}
			}
		} else {
			// Get the cached profile image.
			$twitter_image =  './cache/twitter_'.$tImg.'.jpg';		
		}
	} catch (Exception $e) {
		$twitter_image = '';
	}
	// Return the image URL.
	return $twitter_image;
}
function get_profile_auto() {
	$imageProfile = '';
	$imgD = get_gravatar_profile_img(BLOG_EMAIL);
	if ($imgD == './cache/gravatar.jpg') {
		$imageProfile = $imgD;
	} else {	
		$imgD = get_twitter_profile_img(BLOG_TWITTER);
		if ($imgD == './cache/twitter_d_'.BLOG_TWITTER.'.jpg') {
			$imageProfile = $imgD;
		} else {	
			$imgD = get_twitter_profile_imgV2(BLOG_TWITTER);
			if ($imgD == './cache/twitter_'.BLOG_TWITTER.'.jpg') {
				$imageProfile = $imgD;
			} else {
				$imgD = get_facebook_profile_img(BLOG_FACEBOOK);
				if ($imgD == './cache/facebook_'.BLOG_FACEBOOK.'.jpg') {
					$imageProfile = $imgD;
				} else {
					$imgD = get_tumblr_profile_img(BLOG_TUMBLR);
					if ($imgD == './cache/tumblr_'.BLOG_TUMBLR.'.png') {
						$imageProfile = $imgD;
					} else {				
						$imgD = get_gplus_profile_img(BLOG_GOOGLEP);
						if ($imgD == './cache/gplus_'.BLOG_GOOGLEP.'.jpg') {
							$imageProfile = $imgD;
						}	
					}						
				}		
			}
		}
	}
	if ($imageProfile == '') {
		$imageProfile = './cache/dropplets.jpg';
	}	
	if (file_exists($imageProfile)) {
		return BLOG_URL.str_replace(array(FILE_EXT, './'), '',$imageProfile);
	}
}

function get_profile_img() {
	switch(avatar_default) {
		case "auto":
				return get_profile_auto();
			break;
		case "gravatar":
				$imgD = get_gravatar_profile_img(BLOG_EMAIL);
				$imgDc = './cache/gravatar.jpg';
			break;		
		case "twitter":
				$imgD = get_twitter_profile_img(BLOG_TWITTER);
				$imgDc = './cache/twitter_d_'.BLOG_TWITTER.'.jpg';
			break;
		case "facebook":
				$imgD = get_facebook_profile_img(BLOG_FACEBOOK);
				$imgDc = './cache/facebook_'.BLOG_FACEBOOK.'.jpg';
			break;
		case "tumblr":
				$imgD = get_tumblr_profile_img(BLOG_TUMBLR);
				$imgDc = './cache/tumblr_'.BLOG_TUMBLR.'.png';
			break;
		case "google":
				$imgD = get_gplus_profile_img(BLOG_GOOGLEP);
				$imgDc = './cache/gplus_'.BLOG_GOOGLEP.'.jpg';
			break;	
		default:
				return get_profile_auto();
			break;		
	}
	$imageProfile = '';
	if ($imgD == $imgDc) {
		$imageProfile = $imgD;
	}	
	if ($imageProfile == '') {
		$imageProfile = './cache/dropplets.jpg';
	}	
	if (file_exists($imageProfile)) {
		return BLOG_URL.str_replace(array(FILE_EXT, './'), '',$imageProfile);
	}
}



/*-----------------------------------------------------------------------------------*/
/* defines default blog options 
/*-----------------------------------------------------------------------------------*/
function getPaginationAuto() {
	if(paginationAuto == "on") { 
		echo '<option value="on" selected>' . _t("Pagination On") . '</option>';
        echo '<option value="off">' . _t("Pagination Off") . '</option>';        
	} else {
		echo '<option value="on">' . _t("Pagination On") . '</option>';    
		echo '<option value="off" selected>' . _t("Pagination Off") . '</option>';
	}
}

function getAvatar() {
	$default = avatar_default;	
	$avatares= array( 0 => "auto", 1 => "gravatar", 2 => "twitter", 3 => "facebook",4 => "tumblr", 5 => "google+" );
	foreach($avatares as $avatar){
		if($default == $avatar) { 
			echo '<option value="' . str_replace('+','',$avatar) .'" selected>' . strtoupper($avatar) . '</option>'; 
		} else {
			echo '<option value="' . str_replace('+','',$avatar) .'">' . strtoupper($avatar) . '</option>';
		}
	}
}

function getLanguages() {
	if(isset($_COOKIE['i18nLanguage'])) { 
		$default = trim($_COOKIE['i18nLanguage']); 
	} else {
		$default = language_default;
	}
	$local = './plugins/locale/';
	foreach(glob($local . '*.po') as $lng){
		$lng = str_replace('.po','',str_replace($local,'',$lng));
		if($default == $lng) { 
			echo '<option id="' . $lng . '" value="' . $lng .'" selected>' . _t(str_replace('_','',$lng)) . '</option>'; 
		} else {
			echo '<option id="' . $lng . '" value="' . $lng .'">' . _t(str_replace('_','',$lng)) . '</option>';
		}
	}
}

/*-----------------------------------------------------------------------------------*/
/* String limit - defines the maximum size of the fields Twitter Card and Open Graph Tags
/*-----------------------------------------------------------------------------------*/
function StrLimit($sStrg,$iLmt){
	if (strlen($sStrg)>$iLmt) {
		return substr($sStrg,0,$iLmt);
	} else {
		return $sStrg;
	}
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
	<?php
      	$file = BLOG_PATH . "favicon.png"; // windows compatible
    	if(file_exists($file)) {  
			echo '<link rel="shortcut icon" href="' . BLOG_URL . 'favicon.png">';
		} else {
			echo '<link rel="shortcut icon" href="' . BLOG_URL . 'dropplets/style/images/favicon.png">';
		}
		echo "<!-- jQuery & Required Scripts -->";
      	$file = BLOG_PATH . "src/js/jquery-1.10.2.min.js"; // windows compatible
    	if(file_exists($file)) {  
            echo '<script src="' . BLOG_URL . 'src/js/jquery-1.10.2.min.js"></script>';			
		} else {
			echo '<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>';
		}
        
      	$file = BLOG_PATH . "src/js/modernizr.custom.js"; // windows compatible
    	if(file_exists($file)) {          
            echo "<!-- Modernizr Script -->";        
            echo "<script src='" . BLOG_URL . "src/js/modernizr.custom.js'></script>";
        }
        
        echo "<!-- Fonts Merriweather & Source Sans Pro -->";
		$file = BLOG_PATH . "src/fonts/fonts.css";
    	if(file_exists($file)) {
            echo "<link href='" . BLOG_URL . "src/fonts/fonts.css' rel='stylesheet' type='text/css'>";        
		} else {
			echo "<link href='http://fonts.googleapis.com/css?family=Merriweather:400,300,700' rel='stylesheet' type='text/css'>";
			echo "<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>";
		}     
        
		$file = BLOG_PATH  . "src/font-awesome/css/font-awesome.min.css";
    	if(file_exists($file)) {
            echo "<!-- Fonts Awesome -->";
            echo '<link rel="stylesheet" href="' . BLOG_URL  . 'src/font-awesome/css/font-awesome.min.css">'; 
        }        
    ?>
    <!-- Twitter & Tumblr Scripts -->
    <script src="http://platform.tumblr.com/v1/share.js"></script>
    <script id="twitter-wjs" src="http://platform.twitter.com/widgets.js"></script>
	<!-- jQuery I8N Language Switcher -->
	<script type="text/javascript">
		$(document).ready(function() {
			$("#i18nLanguageOptions").on("change", function() {
				$.cookies.set('i18nLanguage', $("#i18nLanguageOptions option:selected").val());
				location.reload();
			});					
		});
		function OpenWind(w, h, qUrl) {
			window.open(qUrl, 'Window', 'width=' + w + ',height=' + h + ',location=yes,personalbar=no,menubar=no,resizable=yes,status=no,scrollbars=no,toolbar=no');
			return false;
		}
	</script>
    <!-- User Header Injection -->
    <?php echo HEADER_INJECT; ?>
    
    <!-- Plugin Header Injection -->
    <?php action::run('dp_header'); ?>
<?php 

} 

/*-----------------------------------------------------------------------------------*/
/* Dropplets Footer
/*-----------------------------------------------------------------------------------*/
// jquery moved to header
function get_footer() { ?>
	<!-- Post Pagination -->   
    <?php if (!IS_SINGLE && PAGINATION_ON_OFF !== "off") { ?>
    <script type="text/javascript">
			var infinite = true;
			var next_page = 1;
			var loading = false;
			var no_more_posts = false;
			$(function() {
				function load_next_page() {
					$.ajax({
						url: "index.php?page=" + next_page,
						beforeSend: function () {
							$('body').append('<article class="loading-frame"><div class="row"><div class="one-quarter meta"></div><div class="three-quarters"><?php
                                    $file = BLOG_PATH  . "src/imgs/loading.gif";
                                    if(file_exists($file)) {
                                        echo '<img src="' . BLOG_URL . 'src/imgs/loading.gif" alt="Loading" width="80" style="margin-left:20%;float:left;">';
									} else {
										echo '<img src="' . BLOG_URL . 'templates/' . ACTIVE_TEMPLATE . '/loading.gif" alt="Loading" width="180" style="margin-left:20%;float:left;">';	
									}							
							?></div></div></article>');
							$("body").animate({ scrollTop: $("body").scrollTop() + 250 }, 1000);
						},
						success: function (res) {
							next_page++;
							var result = $.parseHTML(res);
							var articles = $(result).filter(function() {
								return $(this).is('article');
							});
							if (articles.length < 2) {  //There's always one default article, so we should check if  < 2
								$('.loading-frame').html(<?php _t("You've reached the end of this list."); ?>);
								no_more_posts = true;
							}  else {
								$('.loading-frame').remove();
								$('body').append(articles.slice(1));
							}
							loading = false;
						},
						error: function() {
							$('.loading-frame').html(<?php _t("An error occurred while loading posts."); ?>);
							//keep loading equal to false to avoid multiple loads. An error will require a manual refresh
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
	<?php				 
		} 
	?>
	<!-- Copyright -->
	<div style="text-align:center; font-size:11px; bottom:0; position: fixed;">
			<?php echo BLOG_COPYRIGHT; ?> - <?php _e("Developed with:"); ?>&nbsp;<a class="dp-link" href="http://dropplets.com" target="_blank">Dopplets</a>&nbsp;&&nbsp;
            <a class="dp-link" href="http://fortawesome.github.io/Font-Awesome/" target="_blank">Font Awesome</a>&nbsp;&&nbsp;<a class="dp-link" href="http://modernizr.com/" target="_blank">Modernizr</a>
	</div>   
    <!-- Dropplets Tools -->
    <?php include('./dropplets/tools.php'); ?>
    
    <!-- User Footer Injection -->
    <?php echo FOOTER_INJECT; ?>
    
    <!-- Plugin Footer Injection -->
    <?php action::run('dp_footer'); ?>
<?php 

}
