<?php
/*
###############################################################################
# Name: The Uniform Server Control Configuration 2.0
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric) 
# Web: http://www.uniformserver.com
# V1.1 28-2-2010
# Comment: Updated to use constants and not variables 
###############################################################################
*/

//=== Path variables - NO BACKSLASH ===
// All paths are absolute referenced to folder UniServer

$path_array     = explode("\\home",dirname(__FILE__));      // Split at folder home
$base_apanel    = "$path_array[0]";                         // find drive letter and any sub-folders 
$base_apanel_f  = preg_replace('/\\\/','/', $base_apanel);  // Replace \ with /


include_once "$base_apanel_f/unicon/main/includes/config.inc.php"; // Load main config

//=== Globals specific to Apanel; ===

$us_apanel_version = "3.0";        // Apanel version
$us_secure         = "1";          // Use secure.php if set to 1

$uniserver = file_get_contents("includes/.version");

$us_devmode = "0";                 //Developer Mode = 1
$us_ip    = getenv("REMOTE_ADDR"); //

$us_apanel = US_HOME."/admin/www"; // Required for language files
$us_www    = "$base_f/www";        // Required for language files
$us_ssl    = "$base_f/ssl";        // Required for language files

?>