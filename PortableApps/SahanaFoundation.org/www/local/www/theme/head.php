<?
/**
 * @name         theme html head
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */

function shn_theme_head() {
	global $global;
	$secure_connection = false;
	if(isset($_SERVER['HTTPS'])){
		if ($_SERVER["HTTPS"] === "on"){
			$secure_connection = true;
		}
	}

	header('Content-type: text/html; charset=UTF-8')
// output html head

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?php echo $global['portable.title'];?></title>


<base href="http:<? if ($secure_connection) echo "s"; echo '//' . $_SERVER['SERVER_NAME'] . '/'; ?>" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Software " content="US National Library of Medicine People Locator" />
<meta name="Website" content="http://pl.nlm.nih.gov" />
<meta name="Licence" content="Lesser General Public Licence, Version 2.1" />
<meta name="Licence Website" content="http://www.gnu.org/licenses/lgpl-2.1.txt" />

<link rel="stylesheet" media="screen, projection" type="text/css" href="theme/sahana.css" />
<link rel="stylesheet" media="print" type="text/css" href="theme/print.css" />
<link rel="stylesheet" media="handheld" type="text/css" href="theme/mobile.css" />
<?
//--- Provide Stylesheets to hack different versions of IEs' css ---//

// IE6
if (file_exists($global['portable.approot']."/www/theme/ie6.css")) { ?>
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" href="theme/ie6.css" />
<![endif]-->
<?
}

// IE7
if (file_exists($global['portable.approot']."/www/theme/ie7.css")) { ?>
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="theme/ie7.css" />
<![endif]-->
<?
}

// IE8
if (file_exists($global['portable.approot']."/www/theme/ie8.css")) { ?>
<!--[if IE 8]>
<link rel="stylesheet" type="text/css" href="theme/ie8.css" />
<![endif]-->
<?
}

// IE9
if (file_exists($global['portable.approot']."/www/theme/ie9.css")) { ?>
<!--[if IE 9]>
<link rel="stylesheet" type="text/css" href="theme/ie9.css" />
<![endif]-->
<?
}
//--- end IE styles ---//
?>
<link rel="icon" type="image/ico" href="favicon.ico">
</head>
<?php
}