<?php 

/*-----------------------------------------------------------------------------------*/
/* If Logged Out, Get the Login Form
/*-----------------------------------------------------------------------------------*/

$login_error = LOGIN_ERROR;

if (!isset($_SESSION['user'])) { ?>

<div id="dp-admin">
    <ul id="dp-tools">
        <li>
            <a class="view-panel<?php if (isset($login_error)) { ?> error<?php } else { ?> dp-login<?php }; ?>" href="#dp-login"><?php if (isset($login_error)) { ?>c<?php } else { ?>d<?php }; ?></a>
        </li>
    </ul>
    
    <div class="dp-panel" id="dp-login">
        <a class="dropplets" href="http://dropplets.com" target="_blank">d</a>
        <a class="dropplets-text" href="http://dropplets.com" target="_blank">Powered by Dropplets</a> 
        
        <form method="POST" action="?action=login">
            <a class="close-panel" href="#dp-login"><img src="<?php echo get_twitter_profile_img(BLOG_TWITTER); ?>" alt="<?php echo(BLOG_TITLE); ?>" /></a>
            
            <h2>Welcome Back!</h2>
            <p>Enter your password below to login.</p>
            
            <fieldset>
                <div class="input">
                    <input type="password" name="password" id="password" placeholder="Enter Your Password">
                </div>
            </fieldset>
            
            <?php if (isset($login_error)) { ?>
                <span><a class="error" href="?action=forgot">Did you forget your password?</a></span>
            <?php } else { ?>
                <span><a href="http://dropplets.com" target="_blank">What is This?</a></span>
            <?php }; ?>
    
            <button type="submit" name="submit" value="submit">k</button>
        </form>    
        
        <a class="dp-close close-panel" href="#dp-login">c</a>   
    </div>
</div>

<?php 

/*-----------------------------------------------------------------------------------*/
/* Otherwise, Get the Toolbar
/*-----------------------------------------------------------------------------------*/

} else { ?>

<div id="dp-admin">
    <ul id="dp-tools">
        <li>
            <label for="postfiles">d</label>
            <input style="display: none;" type="file" name="postfiles" id="postfiles" class="postfiles" multiple="multiple" />
        </li>
        <li><a class="view" href="#dp-settings">s</a></li>
        <li><a class="view" href="#dp-templates">t</a></li>
        <li><a href="?action=logout">l</a></li>
    </ul>
    
    <div class="dp-card post" id="dp-post">
        <div id="publish">
            <div id="dropbox">
                <!-- Drag & Drop Publishing -->
            </div>
    
            <div id="loader">
                <!-- The Publishing Loader -->
            </div>
        </div>
    </div>
    
    <div class="dp-card medium" id="dp-settings">
        <form method="POST" action="./dropplets/save.php">
            <fieldset>
                <div class="input">
                    <label>Blog Email</label>
                    <input type="text" name="blog_email" id="blog_email" required value="<?php echo BLOG_EMAIL; ?>">
                </div>
    
                <div class="input">
                    <label>Blog Twitter ID</label>
                    <input type="text" name="blog_twitter" id="blog_twitter" required value="<?php echo BLOG_TWITTER; ?>">
                </div>
            </fieldset>
    
            <fieldset>
                <div class="input hidden">
                    <input type="text" name="blog_url" id="blog_url" required readonly value="<?php echo BLOG_URL; ?>">
                </div>
    
                <div class="input">
                    <label>Blog Title</label>
                    <input type="text" name="blog_title" id="blog_title" required value="<?php echo BLOG_TITLE; ?>">
                </div>
    
                <div class="input">
                    <label>Blog Description</label>
                    <textarea name="meta_description" id="meta_description" rows="6" required placeholder="Add your site description here... just a short sentence that describes what your blog is going to be about."><?php echo META_DESCRIPTION; ?></textarea>
                </div>
            </fieldset>
    
            <fieldset>
                <div class="input">
                    <label>Intro Title</label>
                    <input type="text" name="intro_title" id="intro_title" required value="<?php echo INTRO_TITLE; ?>">
                </div>
    
                <div class="input">
                    <label>Intro Text</label>
                    <textarea name="intro_text" id="intro_text" rows="12" required><?php echo INTRO_TEXT; ?></textarea>
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
                    <textarea class="code" name="header_inject" id="header_inject" rows="12"><?php echo HEADER_INJECT; ?></textarea>
                </div>
    
                <div class="input">
                    <label>Footer Injection (e.g. Tracking Code)</label>
                    <textarea class="code" name="footer_inject" id="footer_inject" rows="12"><?php echo FOOTER_INJECT; ?></textarea>
                </div>
            </fieldset>
    
            <button type="submit" name="submit" value="submit">k</button>
        </form>
    </div>
    
    <div class="dp-card large" id="dp-templates">
        <label>Installed Templates</label>
        <?php get_installed_templates('all'); ?>
        
        <div class="shadow one"></div>
        <label>Featured Premium Templates</label>
        <?php get_premium_templates('featured'); ?>
        
        <div class="shadow two"></div>
        <label>Popular Premium Templates</label>
        <?php get_premium_templates('popular'); ?>
        
        <div class="shadow three"></div>
        <label>All Premium Templates</label>
        <?php get_premium_templates('all'); ?>
    </div>
</div>

<div id="dp-uploaded"></div>

<?php } 

/*-----------------------------------------------------------------------------------*/
/* Show/Hide Tool Cards Script
/*-----------------------------------------------------------------------------------*/

?>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".view").click(function(){
            $(".view").removeClass('selected'); 
            $(this).addClass('selected');
            var myelement = $(this).attr("href")
            $(myelement).animate({left:"46px"}, 200);
            $(".dp-card:visible").not(myelement).animate({left:"-800px"}, 100);
            $("body").css({ overflow: 'hidden' });
            return false;
        });
        
        $(".view-panel").click(function(){
            var myelement = $(this).attr("href")
            $(myelement).fadeIn(200);
            $("body").css({ overflow: 'hidden' });
            return false;
        });
        
        $(".close-panel").click(function(){
            var myelement = $(this).attr("href")
            $(myelement).fadeOut(100);
            $("body").css({ overflow: 'scroll' });
            return false;
        });
        
        $(document).keydown(function(e) {            
            // Close all panels
            if (e.keyCode == 27) {
                $(".view").removeClass('selected'); 
                $(".dp-card:visible").animate({left:"-800px"}, 100);
                $("body").css({ overflow: 'scroll' });
            }
        });
    });
</script>
    
<?php 

/*-----------------------------------------------------------------------------------*/
/* Get the Uploader Script if Logged In
/*-----------------------------------------------------------------------------------*/

if (isset($_SESSION['user'])) { ?> 

<script type="text/javascript" src="<?php echo(BLOG_URL); ?>dropplets/includes/js/uploader.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function($) {    
        $('.postfiles').liteUploader(
		{
			script: './dropplets/includes/uploader.php',
			// allowedFileTypes: 'image/jpeg,image/jpg,image/png,doc/md', need to figure this out
			maxSizeInBytes: 1048576,
			typeMessage: 'You can put your custom type validation message here',
			before: function ()
			{
				$('#details').html('');
				$('#response').html('Uploading...');
			},
			each: function (file, errors)
			{
				var i, errorsDisp = '';

				if (errors.length > 0)
				{
					$('#response').html('One or more files did not pass validation');

					for (i = 0; i < errors.length; i++)
					{
						errorsDisp += '<br />' + errors[i].message;
					}
				}

				$('#details').append('<p>Name: ' + file.name + ' Type: ' + file.type + ' Size:' + file.size + errorsDisp + '</p>');
			},
			success: function (response)
			{
				$('#dp-uploaded').html(response);
				window.setTimeout(function(){location.reload()},2000)
			}
		});
    });
</script>

<?php } ?>