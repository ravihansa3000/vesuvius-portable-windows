TITLE UNIFORM SERVER - Start MySQL as a program
COLOR B0
@echo off
cls
rem ###################################################
rem # Name: 3_Start_MySQL_program.bat
rem # Created By: The Uniform Server Development Team
rem # Edited Last By: Mike Gleaves (ric)
rem # V 1.0 8-2-2011
rem ##################################################

rem ### working directory current folder 
pushd %~dp0

echo.
rem ### Run MySQL

echo.
echo  Test 1) START MySQL 
echo.
echo  Expected results:
echo   A blank command window opens
echo   Challenged by your firewall
echo   When challenged ALLOW access 
echo   After allowing access command window closes 
echo.
echo  OR
echo.
echo  You are not challenged by your firewall
echo  Command window appears to flash (opens and closes) once.
echo.
echo  Press any key to run START MySQL test.   
echo.
pause
start ..\usr\local\mysql\bin\mysqld-opt.exe

echo.
echo  Test 2) PORT CHECK
echo. 
echo  Run 1_port_check.bat will show port 3306 is now in use
echo.
pause
echo. 
echo  Test 3) SERVER CHECK
echo. 
echo  The following test runs a SQL query on the MySQL server
echo.
echo === Expected results similar to this =====================================
echo ..\usr\local\mysql\bin\mysql  Ver 14.14 Distrib 5.5.9, for Win32 (x86)
echo Connection id:          1
echo Current database:
echo Current user:           root@localhost
echo SSL:                    Not in use
echo Using delimiter:        ;
echo Server version:         5.5.9 MySQL Community Server (GPL)
echo Protocol version:       10
echo Connection:             localhost via TCP/IP
echo Server characterset:    utf8
echo Db     characterset:    utf8
echo Client characterset:    utf8
echo Conn.  characterset:    utf8
echo TCP port:               3306
echo Uptime:                 4 min 29 sec

echo Threads: 1  Questions: 4  Slow queries: 0  Opens: 33  Flush tables: 1  Open tables: 26  Queries per second avg: 0.14
echo --------------
echo Database
echo information_schema
echo mysql
echo performance_schema
echo phpmyadmin
echo ==========================================================================
echo.
echo  To run test 3 Press any key
echo  Note: You may be challenged by your firewall ALLOW access 
echo.
pause
echo.
echo.
echo === Actual results =======================================================
..\usr\local\mysql\bin\mysql -uroot -proot < test.sql
echo ==========================================================================
echo.

pause
echo.
echo  4) FAILS TO START 
echo.
echo     1) Check ports 3306 is not being blocked
echo     2) Always login as Admin
echo     3) Disable the User Account Control (UAC)
echo.
echo  Disable UAC on Windows Vista 
echo.
echo   a) Open up Control Panel, type in "UAC" into the search box.
echo   b) You will see a link for "Turn User Account Control (UAC) on or off" click this link:
echo   c) A new screen opens uncheck the box for "Use User Account Control (UAC)", click the OK button.
echo   d) You must restart Windows.
echo.
echo  Disable UAC on Windows 7
echo.
echo   a) Type UAC into the start menu or Control Panel search box.
echo   b) Drag the slider up or down, defines how often you want to be alerted.
echo   c) Drag it all the way down to the bottom, this disables UAC entirely.
echo.
pause

rem ### restore original working directory
popd
