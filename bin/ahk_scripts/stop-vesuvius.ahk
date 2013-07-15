; Stop Portable Vesuvius instance
; Copyright Sahana Software Foundation

#SingleInstance force
; Get admin priviledges	
if not A_IsAdmin {		
	Run *RunAs "%A_ScriptFullPath%" 
	ExitApp	
}

RunWait, ..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\stop_servers.php", ..\PortableApps\SahanaFoundation.org\usr\local\php


ExitApp