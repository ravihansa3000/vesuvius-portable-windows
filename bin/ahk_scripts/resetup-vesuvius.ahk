; Name: Re-setup Vesuvius Portable instance
; Auhor: Akila Ravihansa Perera <ravihansa3000@gmail.com>
; LastModified: 2013.0811
; License: http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
; Copyright Sahana Software Foundation

#SingleInstance force

RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe "..\..\..\..\..\bin\php_scripts\resetup-vesuvius.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php

Run http://localhost

ExitApp