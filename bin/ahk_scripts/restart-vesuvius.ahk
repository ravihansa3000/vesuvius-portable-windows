; Name: Restart Portable Vesuvius instance
; Auhor: Akila Ravihansa Perera <ravihansa3000@gmail.com>
; LastModified: 2013.0811
; License: http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
; Copyright Sahana Software Foundation

#SingleInstance force
#Include %A_ScriptDir%
SetWorkingDir %A_ScriptDir%
SplashImage, ..\images\splash-screen.png, b, Restarting...Please wait

; Stop portable servers
RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\stop_servers.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide, STOP_PID
Sleep 500

#Include main.inc.ahk

RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\check_ports.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide

; Start portable servers	
RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\start_servers.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide, START_PID

; Import MySQL database dump
RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\import_dbdump.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide

IfExist, %DBImportErrorLog%
{
	SplashImage, Off
	MsgBox 0, Vesuvius Portable, Error! Failed to import database dump. Vesuvius Portable failed to start. See %DBImportErrorLog% for more details.
	RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\stop_servers.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide
	ExitApp
}
else
{
	Run ..\PStart.exe
	Run http://localhost
	Sleep 3000
	SplashImage, Off
}

IfExist, %InitErrorLog% 
{	
	MsgBox 0, Vesuvius Portable, Warning! Some errors occured when initializing Vesuvius Portable. See %InitErrorLog% for more details. 	
}

ExitApp