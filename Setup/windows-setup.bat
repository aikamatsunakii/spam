@echo off

set PATH=%PATH%;C:\xampp\php
set currentDate=%date%

title Sendinbox PROv2 (%currentDate%)
                                                                          


IF EXIST c:\xampp\php\php.exe (
	
	echo =======================================================
	echo             [ SENDINBOX SETUP USER ]
	echo =======================================================

	"c:\xampp\php\php.exe" "installer.php"  "-verifikasi" 
	"c:\xampp\php\php.exe" "installer.php"  "-s" "c:\xampp\php"

	echo [Sendinbox] Generate File Start Sendinbox

	timeout 2 >nul

	cls

	echo =======================================================
	echo             [ SENDINBOX SETUP USER ]
	echo =======================================================

	echo [Sendinbox] Generate File Start Sendinbox ... [DONE]

	timeout 1 >nul

	echo [Sendinbox] UPDATE PHPMAILER
		
	"c:\xampp\php\php.exe" -r "file_put_contents('../Modules/PHPMailer/Exception.php', file_get_contents('https://raw.githubusercontent.com/radenvodka/Sendinbox-/master/PHPMailer/Exception.php'));"
	"c:\xampp\php\php.exe" -r "file_put_contents('../Modules/PHPMailer/OAuth.php', file_get_contents('https://raw.githubusercontent.com/radenvodka/Sendinbox-/master/PHPMailer/OAuth.php'));"
	"c:\xampp\php\php.exe" -r "file_put_contents('../Modules/PHPMailer/PHPMailer.php', file_get_contents('https://raw.githubusercontent.com/radenvodka/Sendinbox-/master/PHPMailer/PHPMailer.php'));"
	"c:\xampp\php\php.exe" -r "file_put_contents('../Modules/PHPMailer/POP3.php', file_get_contents('https://raw.githubusercontent.com/radenvodka/Sendinbox-/master/PHPMailer/POP3.php'));"
	"c:\xampp\php\php.exe" -r "file_put_contents('../Modules/PHPMailer/SMTP.php', file_get_contents('https://raw.githubusercontent.com/radenvodka/Sendinbox-/master/PHPMailer/SMTP.php'));"

	cls

	echo =======================================================
	echo             [ SENDINBOX SETUP USER ]
	echo =======================================================

	echo [Sendinbox] Generate File Start Sendinbox ... [DONE]
	echo [Sendinbox] UPDATE PHPMAILER ... [DONE]
	

) ELSE (
	echo [Sendinbox] XAMPP Tidak ditemukan. Install xampp di drive c:/
)
pause