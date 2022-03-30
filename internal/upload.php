<?php
require_once './ImageCache/ImageCache.php';

$imagecache = new ImageCache\ImageCache();
// Add to siteConfig?
$uploadLocation = './uploads';
$imagecache->cached_image_directory = $uploadLocation;

// Change $uploadObject to be an actual object instead of simple array
function organizeUpload($uploadObject){
    global $uploadLocation;
    $cachedFileRelativePath = $uploadLocation . substr($uploadObject["CachedFileName"], strrpos($uploadObject["CachedFileName"], '/'));

    // Organize the uploads directory
    $year_folder = $uploadLocation . '/' . date("Y");
    $month_folder = $year_folder . '/' . date("m");

    !file_exists($year_folder) && mkdir($year_folder , 0755);
    !file_exists($month_folder) && mkdir($month_folder, 0755);

    $new_file_name = date("Y-m-d-H:i:s") . '.' . $uploadObject["ImageExt"];
    $new_file_path = $month_folder . '/' . $new_file_name;
    $new_URL = $uploadObject["BlogDomain"] . substr($new_file_path, 1);
    rename($cachedFileRelativePath, $new_file_path);
    
    // Return object of [Image URL, Filesystem Path]
    return [$new_URL, $new_file_path];
}

function downloadImage($imageURL, $blogDomain){
    global $imagecache;
    $img = $imagecache->cache($imageURL);
    if (!empty($img)){
    	$imageFileType = strtolower(pathinfo(basename($imagecache->cached_filename),PATHINFO_EXTENSION));
    	$uploadObject = ["ImageExt" => $imageFileType, "CachedFileName" => $imagecache->cached_filename, "BlogDomain" => $blogDomain];
        return organizeUpload($uploadObject);
    }
    else {
        return "ERR";
    }
}

function verifyImage($uploadedFile, $blogDomain){
    global $imagecache;
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
    if ($uploadedFile["size"] > 10485760) {
        // echo "Max file size exceeded (10 MB)";
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

    // If everything is ok, try to upload file
    } else {
        $img = $imagecache->cache($uploadedFile["tmp_name"]);
        if (!empty($img)){
        	$uploadObject = ["ImageExt" => $imageFileType, "CachedFileName" => $imagecache->cached_filename, "BlogDomain" => $blogDomain];
            return organizeUpload($uploadObject);
        }
        else {
            return "ERR";
        }
    }
}