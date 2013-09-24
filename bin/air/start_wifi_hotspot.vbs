Set oShell = CreateObject("WScript.Shell")
Set objShell = CreateObject("Shell.Application")

oShell.Run "cmd.exe /c ""net start ""WLAN AutoConfig"" "" " , 0, true
oShell.Run "cmd.exe /c ""wmic path win32_networkadapter where Name = ""Microsoft Virtual WiFi Miniport Adapter"" call enable "" ", 0, true

strComputer = "."
Set objWMIService = GetObject("winmgmts:\\" & strComputer & "\root\cimv2")
Set colItems = objWMIService.ExecQuery("SELECT * from Win32_NetworkAdapter WHERE Name = ""Microsoft Virtual WiFi Miniport Adapter"" ", ,48)

count = 0
For Each objItem in colItems
	connID = objItem.NetConnectionID	
	count = count + 1
Next

if count = 0 then
	MsgBox "Error! Microsoft Virtual Adapter is not installed"
	WScript.Quit
else
	oShell.Run "cmd.exe /c ""  "" " , 0, true
end if

Set fso = CreateObject("Scripting.FileSystemObject")
currDir = fso.GetParentFolderName(Wscript.ScriptFullName)

If not (fso.FileExists(currDir & "\config.xml")) Then
	MsgBox "Error! config.xml file is missing"
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
oShell.Run "netsh wlan stop hostednetwork" , 0, true
objShell.ShellExecute currDir & "\pskill.exe", "DualServer.exe c", "", , 0

oShell.Run "netsh wlan set hostednetwork mode=allow ssid=" & ssid & " key=" & password , 0, true
oShell.Run "netsh interface ip set address name=""" & connID & """ source=static addr=192.168.0.1 mask=255.255.255.0" , 0, true
oShell.Run "netsh wlan start hostednetwork" , 0, true


objShell.ShellExecute currDir & "\DualServer\DualServer.exe", "-v", "", , 0
