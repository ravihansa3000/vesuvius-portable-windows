<?php
/**
 * @name         Vesuvius Portable debug redirect
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */
 
require_once(realpath(dirname(__FILE__)).'/../lib/main.inc.php');
require_once(realpath(dirname(__FILE__).'/../').'/lib/lib_uuid.php');
require_once(realpath(dirname(__FILE__).'/../').'/lib/lib_portableconfig.php');
require_once($global['portable.approot']."/www/theme/head.php");
require_once($global['portable.approot']."/www/theme/body.php");
require_once($global['portable.approot']."/www/theme/footer.php");

// First check whether Vesuvius Portable is registered
if (file_exists($global['portable.conf_file']) && isUUIDMatch() ) {	
	$host_entry = get_win_machine_name();
	// Redirect client to the debug URL
	header('Location: http://'.$host_entry.'?XDEBUG_SESSION_START=xdebug');
	exit();
}else{
	shn_theme_head();		
	shn_theme_body_block();	
	shn_theme_footer();
	exit();
}