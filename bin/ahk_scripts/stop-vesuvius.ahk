; Stop Portable Vesuvius instance
; Copyright Sahana Software Foundation

#SingleInstance force

RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\stop_servers.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php


ExitApp