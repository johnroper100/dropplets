<?php

// Get existing config.
include('config-settings.php');

$upload_dir = '../posts/';
$allowed_ext = array('jpg','md');

if(strtolower($_SERVER['REQUEST_METHOD']) != 'post'){
	exit_status('Error! Wrong HTTP method!');
}

if(array_key_exists('file',$_FILES) && $_FILES['file']['error'] == 0 ){
	
	$file = $_FILES['file'];

	if(!in_array(get_extension($file['name']),$allowed_ext)){
		exit_status('Only '.implode(',',$allowed_ext).' files are allowed!');
	}	
	
	// Move the uploaded file from the temporary directory to the uploads folder:
	if(move_uploaded_file($file['tmp_name'], $upload_dir.$file['name'])){
		exit_status('File was uploaded successfuly!');
	}
}
exit_status('Something went wrong with your upload!');

// Helper functions
function exit_status($str){
	echo json_encode(array('status'=>$str));
	exit;
}

function get_extension($file_name){
	$ext = explode('.', $file_name);
	$ext = array_pop($ext);
	return strtolower($ext);
}

// Redirect to the set blog url.
header('Location: ' . $blog_url);

?>