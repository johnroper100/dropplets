<?php

session_start();

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}

if (!isset($_SESSION['user'])) {
    exit(json_encode(array('status' => "Unauthorized access")));
}

if (isset($_POST['liteUploader_id']) && ($_POST['liteUploader_id'] == 'postfiles' || $_POST['liteUploader_id'] == 'authorfiles'))
{
  $file_type = $_POST['liteUploader_id'];

  if($file_type == 'postfiles'){
    upload($file_type, array('location' => '../../posts/'));
  }
  
  if($file_type == 'authorfiles'){
    upload($file_type, array('location' => '../../authors/'));
  }

	echo '<span class="success"></span>';
}

function upload($file_type, $options=array()){
	foreach ($_FILES[$file_type]['error'] as $key => $error)
	{
	    if ($error == UPLOAD_ERR_OK)
		{
	        move_uploaded_file( $_FILES[$file_type]['tmp_name'][$key], $options['location'] . $_FILES[$file_type]['name'][$key]);
	    }
	}
}

?>
