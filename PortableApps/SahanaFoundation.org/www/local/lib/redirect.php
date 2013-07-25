<?php
/**
 * @name         Vesuvius Portable remote clients redirect
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */

require_once(realpath(dirname(__FILE__).'/../').'/lib/main.inc.php');
require_once(realpath(dirname(__FILE__).'/../').'/lib/lib_uuid.php');
require_once(realpath(dirname(__FILE__).'/../').'/lib/lib_portableconfig.php');

// First check whether Vesuvius Portable is registered
if (file_exists($global['sahana.conf_file']) && file_exists($global['portable.conf_file']) && file_exists($global['portable.host_uuid_file']) && isUUIDMatch() ) {	
	$host_entry = get_host_entry();
	// Redirect client to the correct URL
	if (strtolower($_SERVER['SERVER_NAME']) !== strtolower($host_entry) ){
		header('Location: http://'. $host_entry);
		exit();
	}else{
		// Access URL is correct, load Vesuvius main script
		error_reporting(E_ALL & ~E_DEPRECATED);
		if (!defined('LC_MESSAGES'))
			define('LC_MESSAGES', 6);
		require_once(realpath(dirname(__FILE__).'/../../').'/vesuvius/www/index.php');
	}		
}else{
	// check whether this is the local user
	if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || $_SERVER['REMOTE_ADDR'] === '::1'){
		// redirect to Vesuvius Portable registration page
		header('Location: http://localhost');
		exit();
	}else{
		// Vesuvius Portable is not yet registered, display the blocked message
		header('Location: http://vesuvius');
		exit();
	}
}