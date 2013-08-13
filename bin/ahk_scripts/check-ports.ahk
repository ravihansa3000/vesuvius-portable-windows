; Name: Check ports
; Auhor: Akila Ravihansa Perera <ravihansa3000@gmail.com>
; LastModified: 2013.0813
; License: http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
; Copyright Sahana Software Foundation

#SingleInstance force
#Include %A_ScriptDir%
#Include main.inc.ahk

RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\check_ports.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php, Hide

IfExist, %ConflictsFile%
{
	Loop, read, %ConflictsFile%
	{
		FileData = %FileData%  `n  %A_LoopReadLine%
	}
	MsgBox 0, Vesuvius Portable, Error! Some ports required by Vesuvius Portable are being used by other processes. Please close the following programs and try again. `n %FileData%
}
else
{
	IfExist, %RunningFile% 
	{		
		Run PStart.exe
		MsgBox 0, Vesuvius Portable, Vesuvius Portable instance is already running. Use Vesuvius Portable Dashboard to launch web browser.
	}
	else 
	{
		MsgBox 0, Vesuvius Portable, Vesuvius Portable is currently stopped.`n`nAll the required ports are free to use. You can start Vesuvius Portable.
	}
}

ExitApp