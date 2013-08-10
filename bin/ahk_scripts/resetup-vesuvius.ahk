; Re-setup Vesuvius Portable instance
; Copyright Sahana Software Foundation

#SingleInstance force

RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\resetup-vesuvius.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php

Run http://localhost

ExitApp