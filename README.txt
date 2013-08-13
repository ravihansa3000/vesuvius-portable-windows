Sahana Vesuvius Portable (beta release)
http://sahanafoundation.org/
10th Aug 2013

Vesuvius Portable 
===========================

Vesuvius is a free and open source Disaster Management System with Hospital Triage and Lost Person Finder Capabilities.It mainly facilitates management of Missing people, disaster victims, Managing and administrating various organisations, managing camps and managing requests and assistance in the proper distribution of resources. With the Portable version of Vesuvius you can have the entire Vesuvius system and data on your USB stick. To launch a Vesuvius instance, all you then have to do is plug in the USB stick into a Windows XP, Vista or Windows 7 machine and start working directly with your instance of Vesuvius (without having to install it in the machine). If your USB disk is slow (ie less than class 4) then it is recommended to unzip it to a local folder on the harddisk.  

Running Vesuvius Portable
-----------------------

The portable app version of Vesuvius does not need to be installed as it is a self-contained Web Server, Database, Web Browser and Vesuvius all packaged withing one folder. This folder can be copied or moved to any location on your harddisk or storage device and can be launched from that location.

To launch Vesuvius run the vesuvius.exe file at the root of this folder

Installing to Hard disk
-----------------------

You can run Vesuvius from the moment you unzip the PortableApp archive, without needing to setup a webserver, database or PHP. Just run vesuvius.exe to lauch the Vesuvius portable dashboard.

Installing to a USB
-------------------

The PortableApp version of Vesuvius can be run from a USB 2.0 disk. To install it to the USB simply unzip the archive to the root (base drive) of the USB disk. A class 6 and above USB device is recommend for or the speed might be too slow.

Installation to USB

   1.download Portable App version
   2.get USB version 2.0 “fast” flash drive with at least 200 Mb of space
   3.unzip the downloaded file into the root directory of the USB disk
   4.run vesuvius.exe from the base directory
   5.When done make sure Vesuvius has been shutdown and safely remove the USB disk

Installation to Harddisk

   1.download Portable App version
   2.unzip the downloaded file into any directory of the harddisk
   3.run vesuvius.exe from the base of the directory in which you unzipped Vesuvius

Troubleshooting
---------------
1) Vesuvius web server does not start up

Vesuvius not starting up could be one of the following issues:

	* Vesuvius Portable must be located in a directory path that does not contain spaces or special characters. This is to avoid known issues with some versions of Windows. Move Vesuvius Portable to a directory path without spaces or special characters and launch vesuvius.exe. For eg. D:\Sahana\vesuvius-portable

    * Vesuvius launches a web server on port 80 and MySQL on 3306. If these ports are currently being utilized by your operating system, Vesuvius will not be able to start. To check what ports are available click on Check Status in the menu and shutdown those respective non-Vesuvius processes

    * Skype occupies Apache HTTPS 443 - you may have to shut own Skype before using the Portable Applet version of Vesuvius

    * If you have problems with response click on Restart Vesuvius Portable

2) Vesuvius is too slow

    * Vesuvius needs a fast USB disk (2.0 at least) as it runs off this disk.

Check if the WebServer (Apache) and Database (MySQL) has started by clicking on the 'Check Server Status' menu item. You should see a report as below indicating port 80 and port 3306 is taken by programs Apache.exe and mysqld-opt.exe from the W: drive

Apache (HTTP)          80   w:\usr\local\apache2\bin\Apache.exe
Apache (HTTPS)        443   free
MySQL                3306   W:\usr\local\mysql\bin\mysqld-opt.exe

If you don't see the above click on 'Stop Vesuvius Portable' followed by 'Restart Vesuvius Portable'. If instead the above ports are taken by another program you need to stop that program before Vesuvius works. This could be another installation of Apache or MySQL that is creating the conflict. 

Portable Developer Environment
------------------------------

The Vesuvius portable application has an integrated development environment and debugger to further empower developers in the response as required. Expand on the Development Tools menu item in the main dashboard and this will display the development menu items. 

Starting the Debugger
----------------------------------------------------------

Here are the steps to get you started debugging on Vesuvius. Most of it already pre-configured so you do not have to worry about it.

   1.Click on Launch IDE/Debugger button, which launches Notepad++ on the main Vesuvius index.php
   2.Click on Plugins -> Dbg -> Debug menu item in Notepad++.
   3.Now click on Debug in default browser, this will launch Vesuvius in Debug mode by appending the ?XDEBUG_SESSION_START=xdebug variable that attaches itself to the Notepad++ debugger. You can now step through the code and set breakpoints, watches, etc as required


Vesuvius version and license details 
-----------------------------------------------------------

Vesuvius is Free and Open Source software released under an LGPL license. This portable package also contains other componentes that are released under other free and Open Source Licenses thus the four freedoms to you as a user are maintained. Freedom to run, study, improve and redistribute. Please also do join our open community and help improve Sahana products at http://sahanafoundation.org/community/developers/

Copyright (C) 2013  Sahana Software Foundation [http://sahanafoundation.org/]

This software is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Sahana Software Foundation; either
version 2.1 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this software; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA