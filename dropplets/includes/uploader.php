<?php

session_start();

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}

if (!isset($_SESSION['user'])) {
    exit(json_encode(array('status' => "Unauthorized access")));
}

if (isset($_POST['liteUploader_id']) && $_POST['liteUploader_id'] == 'postfiles')
{
	foreach ($_FILES['postfiles']['error'] as $key => $error)
	{
	    if ($error == UPLOAD_ERR_OK)
		{
	        move_uploaded_file( $_FILES['postfiles']['tmp_name'][$key], '../../posts/' . $_FILES['postfiles']['name'][$key]);
	    }
	}

	echo '<span class="success"></span>';
}

?>
