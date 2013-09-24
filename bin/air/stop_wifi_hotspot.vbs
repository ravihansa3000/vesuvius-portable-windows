Set oShell = CreateObject("WScript.Shell")
oShell.Run "netsh wlan stop hostednetwork" , 0, true

Set fso = CreateObject("Scripting.FileSystemObject")
currDir = fso.GetParentFolderName(Wscript.ScriptFullName)

Set objShell = CreateObject("Shell.Application")
objShell.ShellExecute currDir & "\pskill.exe", "DualServer.exe c", "", , 0
