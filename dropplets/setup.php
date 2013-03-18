<?php 

/*-----------------------------------------------------------------------------------*/
/* Save Submitted Content
/*-----------------------------------------------------------------------------------*/

if($_POST['submit'] == "submit") 
{
    // Get Stuff
    $author = $_POST['author'];
    $email = $_POST['email'];
    $twitter = $_POST['twitter'];
    $site_url = $_POST['site_url'];
    $title = addslashes($_POST['title']);
    $meta_description = addslashes($_POST['meta_description']);
    $intro_title = addslashes($_POST['intro_title']);
    $intro_text = addslashes($_POST['intro_text']);
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Output Stuff
    $config[] = "<?php";
    $config[] = "\$author = '$author';";
    $config[] = "\$email = '$email';";
    $config[] = "\$twitter = '$twitter';";
    $config[] = "\$site_url = '$site_url';";
    $config[] = "\$title = '$title';";
    $config[] = "\$meta_description = '$meta_description';";
    $config[] = "\$intro_title = '$intro_title';";
    $config[] = "\$intro_text = '$intro_text';";
    $config[] = "\$username = '$username';";
    $config[] = "\$password = '$password';";
    
    // Put Stuff
    file_put_contents("../dropplets/config.php", implode("\n", $config));
    
    // Redirect
    header('Location: ' . '../');
}

?>