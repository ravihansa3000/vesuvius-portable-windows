; Name: Main include script
; Auhor: Akila Ravihansa Perera <ravihansa3000@gmail.com>
; LastModified: 2013.0811
; License: http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
; Copyright Sahana Software Foundation

ConflictsFile = ..\logs\portconflicts.txt
RunningFile = ..\logs\running.txt
DBImportErrorLog = ..\logs\import_error_log.txt
InitErrorLog = ..\logs\init_error_log.txt
InstallDir = %A_ScriptDir%

; Check whether path contains spaces or special characters
FoundInvalidDir := RegExMatch(InstallDir, "[^\w&._:\-\\]")
if FoundInvalidDir <> 0
{
	SplashImage, Off
    MsgBox 0, Vesuvius Portable,Sorry, a space or special character was detected in folder names leading to the folder where Vesuvius Portable is located.`n"%InstallDir%" at index %FoundInvalidDir%`n`nUnfortunately spaces and special characters are not allowed in path names to avoid known issues with some versions of Windows. Please move Vesuvius Portable to a directory location without spaces and try again.`nFor eg. D:\sahana\vesuvius-portable
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

; Check whether Vesuvius Portable instance is already running to avoid duplicates
IfExist, %RunningFile% 
{
	SplashImage, Off
	Run ..\PStart.exe
	MsgBox 0, Vesuvius Portable, Vesuvius Portable instance is already running. Use Vesuvius Portable Dashboard to launch web browser.
	
	ExitApp
}

; Execute initialization tasks - generate ssl keys
RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\init.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide