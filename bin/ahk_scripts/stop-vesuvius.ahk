; Name: Stop Portable Vesuvius instance
; Auhor: Akila Ravihansa Perera <ravihansa3000@gmail.com>
; LastModified: 2013.0813
; License: http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
; Copyright Sahana Software Foundation

#SingleInstance force
#Include %A_ScriptDir%
#Include main.inc.ahk

StopPortableServers()
MsgBox 0, Vesuvius Portable, Vesuvius Portable instance stopped.		

ExitApp