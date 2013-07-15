###############################################################################
# File name: Read_me.txt
# Created By: The Uniform Server Development Team
# V 1.0 16-6-2011
###############################################################################

 ActivePerl is considered to be the de facto standard however the ActiveState
 Community License restricts the way ActivePerl can be distributed. This being
 inconsistent with open source software an ActivePerl plugin is unavailable.
 That said you are free to download, install and use a personal copy of
 ActivePerl Community Edition. For portability it first requires installing and
 then integrating into Uniform Server’s file structure.

 This process is tedious because ActivePerl is distributed only in msi format.
 Although files are extractable without actually performing an installation
 they do require relocating. Uniform Server automates the installation process
 as explained below.

 -------------------
 Download ActivePerl
 -------------------
 First download latest version of ActivePerl Community Edition from
 ActiveState http://www.activestate.com/activeperl/downloads

 * Current version is 5.12.3.1204 file:
   (ActivePerl-5.12.3.1204-MSWin32-x86-294330.msi)
 * Save downloaded file to folder UniServer\a_install_perl

  Note: If you wish, save a copy of the download file. On completing
        installation to save space the one in folder
        UniServer\a_install_perl is deleted.

 -------------------
 Extract and Install
 -------------------
 To extract and Install double click on the batch file:
 UniServer\a_install_perl\extract_install_perl.bat

 For a fresh install process is automatic and does not require any user input.

 If ActivePerl already installed you will be prompted to delete this and
 install new version, press enter to delete and install. Entering anything
 other than “Yes” terminates installation.

 -------------------
 Note shebang update
 -------------------
 After Installation you will need to perform a manual force shebang update.

 ----
 Test
 ----

 Enter the following into browser:
 http://localhost/cgi-bin/test.pl

 Displays "Hello World" confirming correct operation.

------------------------------------ End --------------------------------------

 

