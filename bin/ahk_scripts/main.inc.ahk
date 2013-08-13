; Name: Main include script
; Auhor: Akila Ravihansa Perera <ravihansa3000@gmail.com>
; LastModified: 2013.0813
; License: http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
; Copyright Sahana Software Foundation

SetWorkingDir %A_ScriptDir%

ConflictsFile = %A_ScriptDir%\..\logs\portconflicts.txt
RunningFile = %A_ScriptDir%\..\logs\running.txt
DBImportErrorLog = %A_ScriptDir%\..\logs\import_error_log.txt
SSLErrorLog = %A_ScriptDir%\..\logs\ssl_cert_error_log.txt
InstallDir = %A_ScriptDir%

PreCheck(){
	global InstallDir, RunningFile, ConflictsFile
	; Check whether path contains spaces or special characters
	FoundInvalidDir := RegExMatch(InstallDir, "[^\w&._:\-\\]")
	if FoundInvalidDir <> 0
	{
		SplashImage, Off
		MsgBox 0, Vesuvius Portable,Sorry, a space or special character was detected in folder names leading to the folder where Vesuvius Portable is located.`n"%InstallDir%" at index %FoundInvalidDir%`n`nUnfortunately spaces and special characters are not allowed in path names to avoid known issues with some versions of Windows. Please move Vesuvius Portable to a directory location without spaces and try again.`nFor eg. D:\sahana\vesuvius-portable
		ExitApp
	}
	
	; Check whether Vesuvius Portable instance is already running
	IfExist, %RunningFile% 
	{
		SplashImage, Off
		Run ..\PStart.exe
		MsgBox 0, Vesuvius Portable, Vesuvius Portable instance is already running. Use Vesuvius Portable Dashboard to launch web browser.		
		ExitApp
	}	

	; Check whether ports 80, 443, 3306 are free to use
	RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\check_ports.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide	
	IfExist, %ConflictsFile%
	{
		SplashImage, Off
		Loop, read, %ConflictsFile%
		{
			FileData = %FileData%  `n  %A_LoopReadLine%
		}
		MsgBox 0, Vesuvius Portable, Error! Some ports required by Vesuvius Portable are being used by other processes. Please close the following programs and try again. `n %FileData%		
		ExitApp
	}
}

GenerateSSLCert(){
	RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\ssl_cert_gen.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide
}

StartPortableServers(){
	; Generate new self-signed SSL certificate if machine is moved
	GenerateSSLCert()
	RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\start_servers.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide, START_PID
}

StopPortableServers(){
	RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\stop_servers.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide
}

ImportDatabaseDump(){
	global DBImportErrorLog
	RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\import_dbdump.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide
	; Check whether database import errors were recorded
	IfExist, %DBImportErrorLog%
	{
		SplashImage, Off
		MsgBox 0, Vesuvius Portable, Error! Failed to import database dump. Vesuvius Portable failed to start. See %DBImportErrorLog% for more details.
		StopPortableServers()
		ExitApp
	}
}

CheckSSLErrors(){
	global SSLErrorLog
	IfExist, %SSLErrorLog% 
	{	
		MsgBox 0, Vesuvius Portable, Warning! Some errors occured when initializing Vesuvius Portable. See %SSLErrorLog% for more details. 	
	}
}