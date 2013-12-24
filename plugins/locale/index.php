<?php
$URLRedirector="";
$HTTP_HOST=getenv("HTTP_HOST");
if ($HTTP_HOST != "") {$URLRedirector = "http://$HTTP_HOST/";}
echo "<HTML><HEAD><TITLE>$HTTP_HOST</TITLE><META HTTP-EQUIV=\"Refresh\" Content=0;URL=\"$URLRedirector\">";
echo "</HEAD><BODY></BODY></HTML>";
?>



