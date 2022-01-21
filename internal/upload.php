<?php

function verifyImage($uploadedFile){
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo(basename($uploadedFile['name']),PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($uploadedFile['tmp_name']);
    if($check !== false) {
        // echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size
    if ($uploadedFile["size"] > 5000000) {
        // echo "Max file size exceeded (5 MB)";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    	// echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    	$uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
    	//echo "Sorry, your file was not uploaded.";
    	return "ERR";
    // if everything is ok, try to upload file
    } else {
        $img = file_get_contents($uploadedFile["tmp_name"]);
        if (!empty($img)){
        	$imgEncoded = base64_encode($img);
            return [$imgEncoded, $imageFileType];
        }
        else {
            return "ERR";
        }
    }
}