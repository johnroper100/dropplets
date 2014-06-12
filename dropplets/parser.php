<?php

include('./dropplets/includes/markdown.php');
include('./dropplets/includes/Parsedown.php');

$parser = new MarkdownParser();

class MarkdownParser
{
    function Parse($text) {
    	$use_parsedown = USE_PARSEDOWN;

    	if ($use_parsedown != 'off') {
			$pd = new Parsedown();
			return $pd->text($text);
    	}
        return Markdown($text);
    }
}

?>