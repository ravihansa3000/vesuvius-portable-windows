; Restart Portable Vesuvius instance
; Copyright Sahana Software Foundation

#SingleInstance force
SplashImage, images\splash-screen.png, b, Restarting...Please wait

RunWait, ..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\stop_servers.php", ..\PortableApps\SahanaFoundation.org\usr\local\php, Hide, STOP_PID

Sleep 500

ConflictsFile = logs\portconflicts.txt
RunningFile = logs\running.txt
DBImportErrorLog = logs\import_error_log.txt
InitErrorLog = logs\init_error_log.txt

RunWait, ..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\check_ports.php", ..\PortableApps\SahanaFoundation.org\usr\local\php, Hide

; Check whether ports 80, 443, 3306 are free to use
IfExist, %ConflictsFile%
{
	SplashImage, Off
	Loop, read, %ConflictsFile%
	{
		FileData = %FileData%  `n  %A_LoopReadLine%
	}
	MsgBox 0, Vesuvius Portable Port Checker, Error! Some ports required by Vesuvius Portable are being used by other processes. Please close the following programs and try again. `n %FileData%
}
else 
{
	; Check whether Vesuvius Portable instance is already running to avoid duplicates
	IfExist, %RunningFile% 
	{
		SplashImage, Off
		Run PStart.exe, bin
		MsgBox 0, Vesuvius Portable, Error! Vesuvius Portable could not be restarted.
		ExitApp
	}
	else ; Start portable servers
	{
		; execute initialization tasks - generate ssl keys
		RunWait, ..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\init.php", ..\PortableApps\SahanaFoundation.org\usr\local\php, Hide
		
		IfExist, %InitErrorLog% 
		{			
			SplashImage, Off
			MsgBox 0, Vesuvius Portable, Warning! Some errors occured when initializing Vesuvius Portable. See %InitErrorLog% for more details. 
			SplashImage, images\splash-screen.png, b, Restarting...Please wait
		}
		
		RunWait, ..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\start_servers.php", ..\PortableApps\SahanaFoundation.org\usr\local\php, Hide, START_PID
		
		RunWait, ..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\import_dbdump.php", ..\PortableApps\SahanaFoundation.org\usr\local\php, Hide
		
		IfExist, %DBImportErrorLog%
		{
			SplashImage, Off
			MsgBox 0, Vesuvius Portable, Error! Failed to import database dump. Vesuvius Portable failed to start. See %DBImportErrorLog% for more details.
			RunWait, ..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\stop_servers.php", ..\PortableApps\SahanaFoundation.org\usr\local\php, Hide
			ExitApp
		}
		else
		{
			Run PStart.exe, bin
			Run http://localhost
			Sleep 3000
			SplashImage, Off
		}		
	}
}

ExitApp