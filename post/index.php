<?php session_start();

// User Credentials
include('../dropplets/config.php');

// User Machine
if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {

            // Session Authentication
            case 'login':
                if ((isset($_POST['username']) && isset($_POST['password'])) && ($_POST['username']===$username) && ($_POST['password']===$password))
                {
                    $_SESSION['user']=true;
                    
                    // Redirect
                    header('Location: ' . '../post/'); 
                } else {
                    $login_error = "<strong>Whoops!</strong> Something went wrong, please try again.";
                }
            break;
                
            // End Session    
            case 'logout':
                session_unset();
                session_destroy();
                
                // Redirect
                header('Location: ' . '../post/');
            break;            
                
    }
}

/*-----------------------------------------------------------------------------------*/
/* If Logged Out, Get the Login Panel
/*-----------------------------------------------------------------------------------*/
    
if (!isset($_SESSION['user'])) {

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Login to Publish</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    
    <body>
		<form method="POST" action="?action=login">
            <?php if(isset($login_error)): ?>
            <p class="error"><?php echo $login_error; ?></p>
            <?php endif; ?>
            
            <input type="text" name="username" id="username" placeholder="Username">
            <input type="password" name="password" id="password" placeholder="Password">
            <input type="submit" value="Log in">
        </form>
		
		<a class="home" href="../"></a>
    </body>
</html>
<?php 

} else { 

/*-----------------------------------------------------------------------------------*/
/* Else, If Logged In, Get The Post Panels
/*-----------------------------------------------------------------------------------*/

?> 
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Dropp to Publish</title>
        <link rel="stylesheet" href="style.css" />
    </head>
    
    <body class="dropp">
		<div id="dropbox">
		</div>
		
		<a class="logout" href="?action=logout"></a>
		<a class="home" href="../"></a>
		
		<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
        <script src="script.js"></script>
    </body>
</html>
<?php } ?> 