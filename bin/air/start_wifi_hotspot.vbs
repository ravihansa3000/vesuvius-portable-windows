'
' @name         Vesuvius Air - Start WiFi Hotspot
' @version      0.1
' @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
' @about        Developed in whole or part by the U.S. National Library of Medicine and the Sahana Foundation
' @link         https://pl.nlm.nih.gov/about
' @link         http://sahanafoundation.org
' @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
' @lastModified 2013.0911
'
 
Option Explicit
' Variable declarations
Dim oShell, result, colItems, objItem, connID, objWMIService, strComputer, count, fso, currDir, objXMLDoc, Root, objNodeList, ssid, password, airStatus

airStatus = True
Set oShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")
currDir = fso.GetParentFolderName(Wscript.ScriptFullName)

result = oShell.Run("cmd.exe /c ""net start ""WLAN AutoConfig"" "" " , 0, true)
Call logErrors(result, "WLAN AutoConfig service could not be started")

result = oShell.Run("cmd.exe /c ""wmic path win32_networkadapter where Name = ""Microsoft Virtual WiFi Miniport Adapter"" call enable "" ", 0, true)
Call logErrors(result, "Microsoft Virtual WiFi Miniport Adapter could not be enabled")

strComputer = "."
Set objWMIService = GetObject("winmgmts:\\" & strComputer & "\root\cimv2")
Set colItems = objWMIService.ExecQuery("SELECT * from Win32_NetworkAdapter WHERE Name = ""Microsoft Virtual WiFi Miniport Adapter"" ", ,48)

count = 0
For Each objItem in colItems
	connID = objItem.NetConnectionID	
	count = count + 1
Next

if count=0 then
	'MsgBox "Error! Microsoft Virtual Adapter is not installed"
	Call logErrors(1, "Microsoft Virtual Adapter is not installed")
	WScript.Quit
end if

If not (fso.FileExists(currDir & "\config.xml")) Then
	'MsgBox "Error! config.xml file is missing"
	Call logErrors(1, "config.xml file is missing")
	WScript.Quit
End If
		
Set objXMLDoc = CreateObject("Microsoft.XMLDOM") 
objXMLDoc.async = False 
objXMLDoc.load(currDir & "\config.xml")

Set Root = objXMLDoc.documentElement 
Set objNodeList = Root.getElementsByTagName("ssid")
ssid = objNodeList.item(0).Text
Set objNodeList = Root.getElementsByTagName("password")
password = objNodeList.item(0).Text 

' Stop hostednetwork and kill dual dhcp server if already running
result = oShell.Run("netsh wlan stop hostednetwork" , 0, true)
Call logErrors(result, "Failed to stop hostednetwork")
WScript.Sleep 1000

result = oShell.Run("netsh wlan set hostednetwork mode=allow ssid=" & ssid & " key=" & password , 0, true)
Call logErrors(result, "Failed to set hostednetwork configuration")

WScript.Sleep 1000
result = oShell.Run("netsh interface ip set address name=""" & connID & """ source=static addr=192.168.183.1 mask=255.255.255.0" , 0, true)
Call logErrors(result, "Failed to set interface IP address")
WScript.Sleep 1000
result = oShell.Run("netsh interface ip set dns name=""" & connID & """ source=static addr=192.168.183.1 register=PRIMARY" , 0, true)
Call logErrors(result, "Failed to set interface DNS address")
result = oShell.Run("netsh wlan start hostednetwork" , 0, true)
Call logErrors(result, "Failed to start hostednetwork")
WScript.Sleep 1000

Dim objShell: Set objShell = CreateObject("Shell.Application")
objShell.ShellExecute currDir & "\DualServer\DualServer.exe", "-v", , , 0

if airStatus=False then
	MsgBox "Error! Failed to start Vesuvius Air. See log.txt for more details."
else
	MsgBox "Success! Vesuvius Air has started. Other users can connect to Vesuvius Portable instance via WiFi."
end if

Function logErrors(cmdResult, errMsg)
	Dim objLogFile, logFSO
	if cmdResult=1 then
		Set logFSO = CreateObject("Scripting.FileSystemObject")
		Set objLogFile = logFSO.OpenTextFile(currDir & "\log.txt", 8, True, True)		
		objLogFile.Write Now() & " :: " & errMsg
		objLogFile.WriteLine
		objLogFile.Close	
	end if
End Function