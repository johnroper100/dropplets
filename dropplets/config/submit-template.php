<?php 

/*-----------------------------------------------------------------------------------*/
/* Save Template Selection
/*-----------------------------------------------------------------------------------*/

if($_POST['submit'] == "submit") 
{
    // Get Stuff
    $template = $_POST['template'];
    
    // Output Stuff
    $config[] = "<?php";
    $config[] = "\$template = '$template';";
    
    // Put Stuff
    file_put_contents("../../dropplets/config/config-template.php", implode("\n", $config));
    
    // Redirect
    header('Location: ' . '../../');
}

?>