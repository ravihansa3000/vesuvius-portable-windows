@rem Do not use "echo off" to not affect any child calls.
@setlocal

@rem Get the abolute path to the current directory, which is assumed to be the
@rem Git installation root.
@for /F "delims=" %%I in ("%~dp0") do @set git_install_root=%%~fI
@set PATH=%git_install_root%\bin;%git_install_root%\mingw\bin;%git_install_root%\cmd;%PATH%

@set HOME=%CD%\..\SahanaFoundation.org\www\vesuvius

@set PLINK_PROTOCOL=ssh
@if not defined TERM set TERM=msys

@cd /d %HOME%

git status
pause