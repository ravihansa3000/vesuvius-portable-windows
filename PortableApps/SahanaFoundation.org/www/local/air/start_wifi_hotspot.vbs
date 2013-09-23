Set oShell = CreateObject("WScript.Shell")
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





Set objXMLDoc = CreateObject("Microsoft.XMLDOM") 
objXMLDoc.async = False 
objXMLDoc.load("config.xml")

Set Root = objXMLDoc.documentElement 
Set objNodeList = Root.getElementsByTagName("ssid")
ssid = objNodeList.item(0).Text
MsgBox ssid
Set objNodeList = Root.getElementsByTagName("password")
password = objNodeList.item(0).Text 
MsgBox password

