<?php

/**
 * The primary class file for PHP Image Compressor & Caching
 *
 * This file is to be used in any PHP project that requires image compression
 *
 * @package PHP Image Compressor & Caching
 * @author Erik Nielsen <erik@312development.com | @erikkylenielsen>
 * @license http://freedomdefined.org/Licenses/CC-BY MIT
 * @version GIT: 1.0.0
 *
 * http://dtbaker.net/web-development/how-to-cache-images-generated-by-php/
 */

namespace ImageCache;

ob_start();

class ImageCache {

    const memory_value = 128;

    /**
     * Stores the image source given for reference
     */
    public $image_src;

    /**
     * The version of the source image to which will be part of the cached filename (used to "force" browser to download a new version of the image).
     * @var string
     */
    public $image_src_version;

    /**
     * If the file is remote or not
     */
    public $is_remote;

    /**
     * Allow the user to set the options for the setup
     */
    public $options;

    /**
     * The location of the cached images directory
     */
    public $cached_image_directory;

    /**
     * The version of the cached directory to which will be part of the cached filename (used to "force" browser to download a new version of the entire cache directory).
     * @var string
     */
    public $cached_directory_version;

    /**
     * The URL to access the cached images directory
     */
    public $cached_image_url;

    /**
     * The name of the cached file
     */
    public $cached_filename;

    /**
     * The compression's output quality
     * @var array 
     */
    public $quality;

    /**
     * check link of cached file if is valid by curl
     */
    public $check_link_cached;

    /**
     * Stores the server's version of the GD Library, if enabled
     */
    private $gd_version;

    /**
     * The memory limit currently established on the server
     */
    private $memory_limit;

    /**
     * The file mime type
     */
    private $file_mime_type;

    /**
     * The extension of the file
     */
    private $file_extension;

    /**
     * The original size of the file
     */
    private $local_image_src;

    /**
     * The original size of the file
     */
    private $src_filesize;

    /**
     * The extension of the file
     */
    private $cached_filesize;
    
    /**
     * Constructor function
     *
     * @param array $options Options include the keys 'echo' (boolean) and 'cache_time' (integer).  'cache_time' is currently not employed, but in place for future reference.
     * @return object Returns the class object for the user to reference it in the future.
     */
    public function __construct($options = array()) {
        if (!$this->can_run_image_cache())
            $this->error('PHP Image Cache must be run on a server with a bundled GD version.');
        $defaults = array(
            'check_link_cached' => true, // Check link of cached file if is valid by curl
            'echo' => false, // Determines whether the resulting source should be echoed or returned
            'cache_time' => 0, // How long the image should be cached for. If the value is 0, then the cache never expires. Default is 0, never expires.m
            "quality" => array(// Determines the quality of cache output
                "jpeg" => 85,
                "png" => 8
            ),
            "cached_image_directory" => dirname(__FILE__) . "/php-image-cache",
            "cached_image_url" => "",
            "cached_directory_version" => ""
        );
        $this->options = (object) array_merge($defaults, $options);
        $this->cached_image_directory = $this->options->cached_image_directory;
        $this->cached_image_url = rtrim($this->options->cached_image_url, "/");
        $this->quality = (object) array(
                    "jpeg" => $this->options->quality["jpeg"],
                    "png" => $this->options->quality["png"]
        );
        $this->cached_directory_version = $this->options->cached_directory_version;
        $this->check_link_cached = $this->options->check_link_cached;

        return $this;
    }

    /**
     * Validates whether the user can use this class or not, based on the GD Version their server is carrying.
     *
     * @return bool
     */
    public function can_run_image_cache() {
        $gd_info = gd_info();
        $this->gd_version = false;
        if (preg_match('#bundled \((.+)\)$#i', $gd_info['GD Version'], $matches)) {
            $this->gd_version = (float) $matches[1];
        } else {
            $this->gd_version = (float) substr($gd_info['GD Version'], 0, 3);
        }
        return (bool) $this->gd_version;
    }

    /**
     * Caches the image.  When using this method, the image will be compressed and then cached.
     *
     * @return string The source file to be referenced after compressing an image
     */
    public function cache($image, $version = "") {
		ob_start();
        if ( ! is_writable($this->cached_image_directory))
            $this->error( $this->cached_image_directory . ' must writable!');

        if (!is_string($image))
            $this->error('Image source given must be a string.');

        $this->image_src = $image;
        $this->image_src_version = $version;
        $this->pre_set_class_vars();

        // If the image hasn't been served up at this point, fetch, compress, cache, and return
        if ($this->cached_file_exists()) {
            $this->src_filesize = filesize($this->image_src);
            $this->cached_filesize = filesize($this->cached_filename);
            if ($this->src_filesize < $this->cached_filesize) {
                ob_end_clean();
                return $this->docroot_to_url($this->image_src);
            }
            ob_end_clean();
            return $this->docroot_to_url();
        }
        if ($this->is_remote) {
            $this->download_image();
        }
        if (!$this->fetch_image())
            $this->error('Could not copy image resource.');
        $this->src_filesize = filesize($this->image_src);
        // Delete the temporary source picture because imagedestroy() is no longer capable in PHP 8.0+
        unlink($this->image_src);
        $this->cached_filesize = filesize($this->cached_filename);
        if ($this->src_filesize < $this->cached_filesize) {
            ob_end_clean();
            return $this->docroot_to_url($this->image_src);
        }
        ob_end_clean();
        return $this->docroot_to_url();
    }

    /**
     * Downloads a remote file and stores it locally to be used for compression
     */
    private function download_image() {
        $image_resource = file_get_contents($this->image_src);
		$basename = basename($this->image_src);
        if (!stripos($basename, '.' . $this->file_extension)) {
            $basename .= '.' . $this->file_extension;
        }
        $image_location = dirname($this->cached_image_directory) . '/' . $basename;
        if (!file_exists($image_location)) {
            if (!file_put_contents($image_location, $image_resource)) {
                $this->error('Could not download the remote image');
            }
        }
        $this->image_src = $image_location;
    }

    /**
     * Creates the cached directory
     *
     * @return bool If the directory was successfully created or not
     */
    private function make_cache_directory() {
        if (is_dir($this->cached_image_directory)) {
            return true;
        }
        try {
            mkdir($this->cached_image_directory);
        } catch (Exception $e) {
            $this->error('There was an error creating the new directory:', $e);
            return false;
        }
        return true;
    }

    /**
     * Fetch the image as a resource and save it into the cache directory.
     *
     * @source http://stackoverflow.com/questions/9839150/image-compression-in-php
     * @return If the image was successfully created or not
     */
    private function fetch_image() {
        $image_size = getimagesize($this->image_src);
        $image_width = $image_size[0];
        $image_height = $image_size[1];
        $mime_array = explode('/', $this->file_mime_type);
        $file_mime_as_ext = end($mime_array);
        $image_dest_func = 'imagecreate';
        if ($this->gd_version >= 2)
            $image_dest_func = 'imagecreatetruecolor';
        if (in_array($file_mime_as_ext, array('gif', 'jpeg', 'png'))) {
            $image_src_func = 'imagecreatefrom' . $this->file_extension;
            $image_create_func = 'image' . $this->file_extension;
        } else {
            $this->error('The image you supply must have a .gif, .jpg/.jpeg, or .png extension.');
            return false;
        }
        $this->increase_memory_limit();
        $image_src = @call_user_func($image_src_func, $this->image_src);
        $image_dest = @call_user_func($image_dest_func, $image_width, $image_height);
        if ($file_mime_as_ext === 'jpeg') {
            $background = imagecolorallocate($image_dest, 255, 255, 255);
            imagefill($image_dest, 0, 0, $background);
        } elseif (in_array($file_mime_as_ext, array('gif', 'png'))) {
            imagealphablending($image_src, false);
            imagesavealpha($image_src, true);
            imagealphablending($image_dest, false);
            imagesavealpha($image_dest, true);
        }
        imagecopy($image_dest, $image_src, 0, 0, 0, 0, $image_width, $image_height);
        switch ($file_mime_as_ext) {
            case 'jpeg':
                $created = imagejpeg($image_dest, $this->cached_filename, $this->quality->jpeg);
                break;
            case 'png':
                $created = imagepng($image_dest, $this->cached_filename, $this->quality->png);
                break;
            case 'gif':
                $created = imagegif($image_dest, $this->cached_filename);
                break;
            default:
                return false;
                break;
        }
        unset($image_src);
        unset($image_dest);
        $this->reset_memory_limit();
        return $created;
    }

    /**
     * Returns
     *
     * @param string $src The url to check validate
     * @return string The URL of the image
     */
    private function docroot_to_url($src = null) {
        if (is_null($src)) {
            $src = $this->cached_filename;
        }
        if (empty($this->cached_image_url)) {
            $image_path = str_replace($_SERVER['DOCUMENT_ROOT'], '', $src);
            $image_url =  $_SERVER['REQUEST_SCHEME']."://" . $_SERVER['HTTP_HOST'].'/' . substr($image_path, 1);
        } else {
            $image_url = $this->cached_image_url . "/" . basename($src);
        }
        if ($this->link_is_broken($image_url)) {
            $this->error('Final image URL is broken');
        }
        return $image_url;
    }

    /**
     * Sets up all class variables in one central function.
     */
    private function pre_set_class_vars() {
        $this->set_file_mime_type();
        $this->set_cached_filename();
        $this->set_memory_limit();
        $this->set_is_remote();
    }

    /**
     * Utility function to determine of the link in question is broken or not.
     *
     * @param string $url The url to check validate
     * @return bool Indicates whether or not the link is broken
     */
    private function link_is_broken($url) {
        if (!$this->check_link_cached || !function_exists('curl_init'))
            return false;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 404) {
            $broken = true;
        } else {
            $broken = false;
        }
        curl_close($ch);
        return $broken;
    }

    /**
     * Quick and dirty way to see if the file is remote or local.  Deeper checking comes
     * later if we don't find a compressed version of the file locally.
     *
     * @return bool Whether or not the image is remote
     */
    private function is_image_local() {
        if (file_exists($this->image_src))
            return true;
        $parsed_src = parse_url($this->image_src);
        if ($_SERVER['HTTP_HOST'] === $parsed_src['host'])
            return true;
        return false;
    }

    /**
     * Determines if the image is local or being referenced from a remote URL
     */
    private function set_is_remote() {
        $this->is_remote = !$this->is_image_local();
    }

    /**
     * This function creates the filename of the new image using an MD5 hash of the original filename.  This helps to create a unique
     * filename for the newly compressed image so that this class can easily find and determine if the image has already been compressed and stored locally.
     *
     * Employing use of the "alt" and "title" tags on the image (which hopefully you're already doing) will negate any potentially negative impacts on SEO.  See
     * this article for more information: http://searchenginewatch.com/article/2120682/Image-Optimization-How-to-Rank-on-Image-Search
     */
    private function set_cached_filename() {
        $pathinfo = pathinfo($this->image_src);
        $this->cached_filename = $this->cached_image_directory . '/' . md5($this->cached_directory_version . basename($this->image_src) . $this->image_src_version) . '.' . $this->file_extension;
    }

    /**
     * Simply determines if a compressed of the image that's sent is already compressed or not.
     */
    private function cached_file_exists() {
        if ($this->is_remote) {
            $this->download_image();
        }
        if (file_exists($this->cached_filename))
            return true;
        return false;
    }

    /**
     * Stores the file's mime type and validates that the file being compressed is indeed an image.
     */
    private function set_file_mime_type() {
        $image_type = exif_imagetype($this->image_src);
        if (!$image_type)
            $this->error('The file you supplied isn\'t a valid image.');
        $this->file_mime_type = image_type_to_mime_type($image_type);
        $this->file_extension = image_type_to_extension($image_type, false);
    }

    /**
     * Stores the original value of the server's memory limit
     */
    private function set_memory_limit() {
        $this->memory_limit = ini_get('memory_limit');
    }

    /**
     * Temporarily increases the servers memory limit to 2480 MB to handle building larger images.  Based on initial
     * tests, this seems to be a large enough increase to handle rebuilding jpg images as large as 4300px wide with no pre-compression.
     */
    private function increase_memory_limit() {
        ini_set('memory_limit', '2480M');
    }

    /**
     * Resets the servers memory limit to its original value
     */
    private function reset_memory_limit() {
        ini_set('memory_limit', $this->memory_limit);
    }

    /**
     * Displays an error and kills the script
     *
     * @param String $status The message to be passed to the native `exit()` function
     */
    private function error($status = null) {
        if (is_null($status))
            $status = 'Unknown Error:';
        exit($status);
    }

}