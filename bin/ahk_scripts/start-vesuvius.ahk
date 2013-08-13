; Name: Launch Portable Vesuvius instance
; Auhor: Akila Ravihansa Perera <ravihansa3000@gmail.com>
; LastModified: 2013.0813
; License: http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
; Copyright Sahana Software Foundation

#SingleInstance force
#Include %A_ScriptDir%
#Include main.inc.ahk
SetWorkingDir %A_ScriptDir%

; Show loading screen
SplashImage, ..\images\splash-screen.png, b, Loading...Please wait

; Check whether all pre-requisites are fully met
PreCheck()

; Start portable servers
StartPortableServers()

; Import MySQL database dump
ImportDatabaseDump()

Run ..\PStart.exe
Run http://localhost
Sleep 3000
SplashImage, Off
CheckSSLErrors()
ExitApp