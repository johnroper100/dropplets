Internationalization
=========

The internationalization of systems on Windows using Apache / PHP with UTF-8, setlocale and gettext has documented problems. 
I tested several solutions and alternatives to operate simply and none had a productive outcome. 

If you want to use another approach to the problem, you have some links below that might help.

This solution did not use the files .mo but files .po.

These files can be used if this problem does not occur in a future release of WAMP's.
Despite files .po are text files, use programs such as Poedit to translate. 

This will ensure the integrity of the translation and the system I developed to read the files.


### Original Directory structure of locale is (for example): 
	locale/en_US/LC_MESSAGES/en_US.mo 
	locale/pt_BR/LC_MESSAGES/pt_BR.mo 

### Directory structure used:
	./plugins/locale/en_US.po 
	./plugins/locale/pt_BR.po 
		
### Languages:
	en_US = English 
	pt_BR = Portuguese(Brasil)	

## Functions:
	_e('Key to search'); => Return: echo 'Translation'; 
	_t('Key to search'); => Return: 'Translation'; 

## WARNING:
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

## How to Translate:
- Copy the en_US.po file (from locale directory) to one of the names below::
> 	da_DK, fr_FR, de_DE, el_GR, he_IL, it_IT, ja_JP, ko_KR, ru_RU, nl_NL, zh_CN, zh_TW
- Use an editor like Poedit language
- Open the file chosen for translation.
- Write the corresponding translation
- Share on GitHub.


## License
Copyright (c) 2013 Marcio Barbosa a.k.a. MBarbosaEng

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sub license, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANT ABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.