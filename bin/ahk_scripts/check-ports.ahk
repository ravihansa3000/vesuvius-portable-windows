; Stop Portable Vesuvius instance
; Copyright Sahana Software Foundation

#SingleInstance force
ConflictsFile = ..\logs\portconflicts.txt
RunningFile = ..\logs\running.txt

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
		MsgBox 0, Vesuvius Portable, Vesuvius Portable is currently stopped.`n`nPort Checker test passed! All the required ports are free to use. You can start Vesuvius Portable.
	}
}

ExitApp