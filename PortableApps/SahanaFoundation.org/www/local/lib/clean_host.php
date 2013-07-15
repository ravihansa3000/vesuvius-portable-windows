<?php
/**
 * @name         Vesuvius Portable hosts file clean-up
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */

/* Clean Windows hosts file - remove any previously added entries */
function clean_host(){
	global $global;	
	
	$tmp_host_file = $global['portable.tmp_dir']."/tmp_hosts";
	$win_host_file = $global['portable.win_host_file'];
	copy($win_host_file, $tmp_host_file);
	
	$sh = fopen($tmp_host_file, 'r');
	$th = fopen($win_host_file, 'w');
	
	if (!($sh && $th)){
		return false;
	}
		
	while (($buffer = fgets($sh)) !== false) {
		// check for lines that contain Vesuvius Portable host entry tag
		if (!(substr(trim($buffer),0,1) !== '#' && strpos($buffer, $global['portable.host_entry_tag']) !== false)) {
			fwrite($th, $buffer);
		}		
	}
	
	fclose($sh);
	fclose($th);
	unlink($tmp_host_file);
	return true;
}
 ?>