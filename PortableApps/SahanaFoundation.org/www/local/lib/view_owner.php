<?php
/**
 * @name         View Owner
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0811
 */
 
require_once(realpath(dirname(__FILE__).'/../').'/lib/main.inc.php');
require_once($global['portable.approot'].'/lib/lib_portableconfig.php');
require_once($global['portable.approot'].'/lib/lib_uuid.php');
require_once($global['portable.approot']."/www/theme/head.php");
require_once($global['portable.approot']."/www/theme/body.php");
require_once($global['portable.approot']."/www/theme/footer.php");

// First check whether Vesuvius Portable is registered 
if (file_exists($global['portable.conf_file'])){	
	shn_theme_head();
	shn_theme_body_view_owner();	
	shn_theme_footer();
	exit();
}else{ // if not display blocked message
	shn_theme_head();
	shn_theme_body_block();	
	shn_theme_footer();
	exit();
}