<?php

/*-----------------------------------------------------------------------------------*/
/* Save Submitted Content
/*-----------------------------------------------------------------------------------*/

if($_POST['submit'] == "submit")
{
    include('user.class.php');
    // Get Stuff
    $author = $_POST['author'];
    $email = $_POST['email'];
    $twitter = $_POST['twitter'];
    $site_url = $_POST['site_url'];
    $title = addslashes($_POST['title']);
    $meta_description = addslashes($_POST['meta_description']);
    $intro_title = addslashes($_POST['intro_title']);
    $intro_text = addslashes($_POST['intro_text']);
    $password = $_POST['password'];

    // generate hash string for crypt
    $hash_string = md5( uniqid() );
    // hash password
    $password = User::hash_password( $password, $hash_string );

    // Output Stuff
    $config[] = "<?php";
    $config[] = "define( 'HASH_STRING', '" . $hash_string . "' );";
    $config[] = "\$author = '$author';";
    $config[] = "\$email = '$email';";
    $config[] = "\$twitter = '$twitter';";
    $config[] = "\$site_url = '$site_url';";
    $config[] = "\$title = '$title';";
    $config[] = "\$meta_description = '$meta_description';";
    $config[] = "\$intro_title = '$intro_title';";
    $config[] = "\$intro_text = '$intro_text';";
    $config[] = "\$password = '$password';";

    // Put Stuff
    file_put_contents("../dropplets/config.php", implode("\n", $config));

    // Redirect
    header('Location: ' . '../');
}

?>