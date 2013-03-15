<?php session_start();

// User Credentials
include('../dropplets/config.php');
include('../dropplets/user.class.php');
$user = new User();
// User Machine
if (isset($_GET['action'])) {
        $action = $_GET['action'];
        switch ($action) {

            // Session Authentication
            case 'login':
                if (isset($_POST['password'])){
                    $user->log_in($_POST['password']);

                    // Redirect
                    header('Location: ' . '../post/');
                    exit;
                }

                $login_error = "<strong>Whoops!</strong> Something went wrong, please try again.";
            break;

            // End Session
            case 'logout':
                $user->log_out();

                // Redirect
                header('Location: ' . '../post/');
                exit;
            break;

    }
}

/*-----------------------------------------------------------------------------------*/
/* If Logged Out, Get the Login Panel
/*-----------------------------------------------------------------------------------*/

if (!$user->is_logged_in()) {

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

            <input type="password" name="password" id="password">
		</form>

		<a class="home" href="../"></a>
    </body>
</html>
<?php

} else {

/*-----------------------------------------------------------------------------------*/
/* Else, If Logged In, Get The Admin Panels
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

		<script src="http://code.jquery.com/jquery-1.9.0.js"></script>
        <script src="script.js"></script>
    </body>
</html>
<?php } ?>