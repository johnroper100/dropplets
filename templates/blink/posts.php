<?php
/*
 * Goal: for file.php in $template_dir_name/posts; if ($post_status == $file){ include '$file';}
 *
 * foreach (glob("*.txt") as $filename) {}
 *
 *
 *
 */
/*
// this runs for 
// this code returns three copies of the relevant post, not always with the title. 
foreach (glob($template_dir . "posts/*.php") as $filename) {
    // that runs for each post in the dir
    // This next bit from http://txt2re.com/index-php.php3?s=./templates/benlk.com/posts/aside.php&8
    
    $re1='.*?';	# Non-greedy match on filler
    $re2='(?:[a-z][a-z]+)';	# Uninteresting: word
    $re3='.*?';	# Non-greedy match on filler
    $re4='(?:[a-z][a-z]+)';	# Uninteresting: word
    $re5='.*?';	# Non-greedy match on filler
    $re6='(?:[a-z][a-z]+)';	# Uninteresting: word
    $re7='.*?';	# Non-greedy match on filler
    $re8='(?:[a-z][a-z]+)';	# Uninteresting: word
    $re9='.*?';	# Non-greedy match on filler
    $re10='((?:[a-z][a-z]+))';	# Word 1
    if ($c=preg_match_all ("/".$re1.$re2.$re3.$re4.$re5.$re6.$re7.$re8.$re9.$re10."/is", $filename, $matches))
    {
        include $filename;
    } else {
        include 'posts/posts.php';
    }
}
*/

    //old code, which doesn't update for arbitrary new post statuses relevant to new files
 
    if ($post_status == 'aside' ) {
        include 'posts/aside.php';
    } elseif ($post_status == 'feature' ) {
        include 'posts/feature.php';
    } elseif ($post_status == 'revealjs' ) {
        include 'posts/revealjs.php';
    } else {
        include 'posts/posts.php';
    }

?>

