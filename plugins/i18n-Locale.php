<?php
/*
 * Plugin Name: I18n for Dropplets & BitZero
 * Author: MBarbosaEng <MBarbosaEng@EngBit.com.br>
 * Twitter: @MBarbosaEng
 * Plugin filename: i18n-Locale
 * Version: 1.0.0
 */

 /*
The internationalization of systems on Windows using Apache / PHP with UTF-8, setlocale and gettext has documented problems. 
I tested several solutions and alternatives to operate simply and none had a productive outcome. 
If you want to use another approach to the problem, you have some links below that might help.
This solution did not use the files .mo but files .po.
These files can be used if this problem does not occur in a future release of WAMP's.
Despite files .po are text files, use programs such as Poedit to translate. 
This will ensure the integrity of the translation and the system I developed to read the files.

A internacionalização de sistemas no Windows utilizando Apache / PHP com UTF-8, setlocale e gettext possui problemas documentados. 
Testei várias soluções e alternativas para fazer funcionar de maneira simples e nenhum teve um resultado produtivo. 
Se desejar utilizar uma outra abordagem ao problema, abaixo você tem alguns links que talvez possa ajudar.
Nesta solução não utilizei os arquivos .mo e sim os arquivos .po. 
Estes arquivos podem ser utilizados caso este problema não ocorra numa futura release de WAMP's. 
Apesar dos arquivos .po serem arquivos texto, utilize programas como o Poedit para  fazer a tradução. 
Isto irá garantir a integridade da tradução e do sistema que desenvolvi para ler os arquivos. 
 
	http://www.gnu.org/software/gettext/manual/gettext.html
	http://include-once.org/p/upgradephp/
	http://svn.apache.org/repos/asf/subversion/trunk/notes/l10n-problems
	http://cygwin.com/cygwin-ug-net/setup-locale.html
	http://www.postgresql.org/message-id/2505.1272378347@sss.pgh.pa.us
	http://supertuxkart.sourceforge.net/Gettext_on_windows
	http://www.php.net/manual/pt_BR/function.strftime.php - http://www.php.net/manual/pt_BR/function.date.php
	http://docs.moodle.org/dev/Table_of_locales http://www.w3.org/WAI/ER/IG/ert/iso639.htm
	http://msdn.microsoft.com/en-us/library/39cwe7zf%28v=vs.100%29.aspx
	http://php.net/manual/pt_BR/function.setlocale.php 
 
 
	Original Directory structure of locale is (for example): 
	locale/en_US/LC_MESSAGES/en_US.mo 
	locale/pt_BR/LC_MESSAGES/pt_BR.mo 

	Directory structure used:
	./plugins/locale/en_US.po 
	./plugins/locale/pt_BR.po 
		
	Languages:
	en_US = English 
	pt_BR = Portuguese(Brasil -> Brasil isn't with Z!!!)	

	Functions:
	
	_e('Key to search'); => Return: echo 'Translation'; 
	_s('Key to search'); => Return: 'Translation'; 

	WARNING:
	This plugin has a limitation:
	If you exceed the 79 column, Poedit broken into two lines.
	Since the plugin reads the po files, it will not find the corresponding variable/response.
	If you want to make sure it is correct, open the po file in a text editor like Notepad++ or PSPad.
	
		DON'T PASS 79 COLUMN LIMIT

	1	                                                                           79
    |                                                                              |
    msgid "Click the button below to find something else that might interest you."
    msgstr "Clique no botão abaixo para encontrar outra coisa que pode lhe interessar."   <== WRONG
	
    msgid "Click the button below to find something else that might interest you."
    msgstr "Clique no botão abaixo e encontre algo que talvez possa lhe interessar."	  <== WRONG
    
    msgid "Click the button below to find something else that might interest you."
    msgstr "Clique no botão abaixo e encontre algo que possa lhe interessar."             <== OK
	|																			   |	
*/ 

//$language = 'en_US';
//$language = 'pt_BR';
$folder = './plugins/locale';

$timeZone = 'GMT';  // set yours - see http://us.php.net/manual/en/timezones.others.php


// For Windows, you need $lngWin   
if ($language == 'en_US') {
	$date_format = '%B %d, %Y'; // $date_format = 'F jS, Y'; // January 3th, 2013
	$encoding = 'UTF-8';	
    $lngWin = 'English'; 
} else if ($language == 'pt_BR') {
 	$date_format = '%d/%b/%Y'; // '%d/%m/%Y'; 
	$encoding = 'UTF-8';
    $lngWin =  'Portuguese_Brazil.1252';
} else {
	$date_format = '%d/%B, %Y';
    $lngWin = 'English'; 
	$encoding = 'UTF-8';	 
}

// save language preference for future page requests
define("language",$language);
define("date_format",$date_format);
define("lngWin",$lngWin);
define("encoding",$encoding);
define("timeZone",$timeZone);
define("folder",$folder); 
define("domain",$language); 
define("message",$language.'.'.$encoding);
	
    // for windows compatibility http://msdn.microsoft.com/en-us/library/4zx9aht2(VS.80).aspx
	if (!defined('LC_MESSAGES')) 	define('LC_MESSAGES', 6);		
	// Linux
	if ($language == 'en_US') {
		setlocale(LC_CTYPE, 'C');
		setlocale(LC_MESSAGES,'C');
		setlocale(LC_ALL, 'C');
	} else {
		setlocale(LC_CTYPE, message);
		setlocale(LC_MESSAGES, message);
		//setlocale(LC_ALL,message);
		setlocale(LC_ALL ^ LC_MESSAGES, message);
	}
	// for windows compatibility
    putenv('LANG='.message);
	putenv('LANGUAGE='.message);
    putenv('LC_ALL='.message); 
	putenv('LC_MESSAGES='.message); 
	

/* 
Don't work on Windows: gettext("keyword"); or _("keyword");
bindtextdomain(domain,folder); 				
textdomain(domain); 						
bind_textdomain_codeset(domain, encoding);
*/
function _t($word){  //==> _("HELLO_WORLD");
	try{	
		$word = strtolower($word);
		$contents = file(folder . '/' . language . '.po');
		$searchtxt = array_map('strtolower',$contents);
		$pos = array_flip(preg_grep('/' . $word . '/', $searchtxt));
		if ($pos !== false) {
			return preg_replace('/\n/','',preg_replace('/"/','',preg_replace('/msgstr /','',$contents[array_shift($pos)+1])));
		} else {
			return '';		
		}
	} catch (Exception $e) {
		return '';
	}
}

function _e($var){
	echo _t($var); //==> echo _("HELLO_WORLD");
}
function _n($var, $number){
	try{
		return preg_replace('/%s/',$number,_t($var));
	} catch (Exception $e) {
		return '';
	}		
}

ffunction localDate($dt){
    $dt = str_replace("\\", "-", $dt);
    $dt = str_replace("/", "-", $dt);
    if (strlen($dt)<10) {
        return '';
    } else {
        try{
            date_default_timezone_set(timeZone);
            setlocale(LC_ALL,message,lngWin);
            $aData = explode('-',$dt);
            return strftime(date_format,mktime(0,0,0,(int)$aData[1],(int)$aData[2],(int)$aData[0]));
        } catch (Exception $e) {
            return '';
        }
    }
}

?>