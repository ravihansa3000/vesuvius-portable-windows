'
' @name         Vesuvius Air - Stop WiFi Hotspot
' @version      0.1
' @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
' @about        Developed in whole or part by the U.S. National Library of Medicine and the Sahana Foundation
' @link         https://pl.nlm.nih.gov/about
' @link         http://sahanafoundation.org
' @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
' @lastModified 2013.0911
'

Set oShell = CreateObject("WScript.Shell")
oShell.Run "netsh wlan stop hostednetwork" , 0, true

Set fso = CreateObject("Scripting.FileSystemObject")
currDir = fso.GetParentFolderName(Wscript.ScriptFullName)

oShell.Run currDir & "\pskill.exe DualServer.exe c", 0, true

MsgBox "Vesuvius Air is now stopped."
