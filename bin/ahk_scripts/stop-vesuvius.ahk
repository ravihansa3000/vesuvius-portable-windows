; Name: Stop Portable Vesuvius instance
; Auhor: Akila Ravihansa Perera <ravihansa3000@gmail.com>
; LastModified: 2013.0811
; License: http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
; Copyright Sahana Software Foundation

#SingleInstance force

RunWait, ..\..\PortableApps\SahanaFoundation.org\usr\local\php\php.exe -n  "..\..\..\unicon\main\stop_servers.php", ..\..\PortableApps\SahanaFoundation.org\usr\local\php


ExitApp