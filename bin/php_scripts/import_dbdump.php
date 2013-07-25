<?php
/**
 * @name         Import Database Dump
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0725
 */

require_once(realpath(dirname(__FILE__).'/../../').'/PortableApps/SahanaFoundation.org/www/local/lib/main.inc.php');
chdir(dirname(__FILE__)); // Change wd to this files location

$output_file = realpath(dirname(__FILE__). '/../logs').'/import_error_log.txt';
$std_err_file = "..\logs\import_error_log.txt";
$tmp_dbdump = dirname(__FILE__)."/dbdump.tmp";

if (file_exists($output_file)){
	unlink($output_file);
}

// check whether this is the first run. If not don't bother with db import
if (file_exists($global['portable.host_uuid_file'])){
	echo "Already initialized!" . PHP_EOL;
	exit();
}

if (file_exists($global['portable.db_dump_file'])){ // db dump file must exist
	copy($global['portable.db_dump_file'], $tmp_dbdump);
	/* Remove any DEFINER entries to avoid any MySQL permission issues */
	$pattern = "DEFINER[ ]*=[ ]*[^*]*";
	$sh = fopen($global['portable.db_dump_file'], 'r');
	$th = fopen($tmp_dbdump, 'w');
	if ($sh && $th){
		while (($buffer = fgets($sh)) !== false) {
			$buffer = preg_replace("/".$pattern."/", "", $buffer);
			fwrite($th, $buffer);
		}
		fclose($sh);
		fclose($th);
		
		/* execute SQL queries in database dump file */
		
		/* First drop the database if it already exists */
		exec($global['portable.mysql.exe'] . ' --user='.$global['portable.dbuser'].' --password='.$global['portable.dbpasswd'].' --host='.$global['portable.host'].' --port='.$global['portable.dbport'].' --execute="DROP DATABASE IF EXISTS '.$global['portable.dbname'].';" 2>>'.$std_err_file, $output, $return_var);
		
		if ($return_var !== 0){ // return value should be 0 on success
			file_put_contents($output_file, "Error: " . serialize($output) . PHP_EOL , FILE_APPEND);
		}
		
		/* Create a new database */
		exec($global['portable.mysql.exe'] . ' --user='.$global['portable.dbuser'].' --password='.$global['portable.dbpasswd'].' --host='.$global['portable.host'].' --port='.$global['portable.dbport'].' --execute="CREATE DATABASE '.$global['portable.dbname'].';" 2>>'.$std_err_file, $output, $return_var);
		
		if ($return_var !== 0){ // return value should be 0 on success
			file_put_contents($output_file, "Error: " . serialize($output) . PHP_EOL , FILE_APPEND);
		}
		
		/* Execute queries in database dump file */
		exec($global['portable.mysql.exe'] . ' --user='.$global['portable.dbuser'].' --password='.$global['portable.dbpasswd'].' --host='.$global['portable.host'].' --port='.$global['portable.dbport'].' '.$global['portable.dbname'].' < ' . $tmp_dbdump . ' 2>>'.$std_err_file, $output, $return_var);

		if ($return_var !== 0){ // return value should be 0 on success
			file_put_contents($output_file, "Error: " . serialize($output) . PHP_EOL , FILE_APPEND);
		}
		
		if (file_exists($output_file) && file_get_contents($output_file) === ""){
			unlink($output_file); // remove error log file if it is empty
		}
		unlink($tmp_dbdump);
		
		if (!file_exists($output_file)){ 
			// remove dbdump only if db import is success
			rename($global['portable.db_dump_file'], $global['portable.db_dump_file'].'.bak');
			
			// create host_uuid file with a dummy UUID, this will trigger a machine movement in Vesuvius Portable Manager
			if (!file_put_contents($global['portable.host_uuid_file'], "0")){
				file_put_contents($output_file, "Error writing file: " . $global['portable.host_uuid_file'] . PHP_EOL , FILE_APPEND);
			}
		}
	}else{
		file_put_contents($output_file, "Error reading file: " . $global['portable.db_dump_file'] . PHP_EOL , FILE_APPEND);
	}
}else{
	file_put_contents($output_file, "Database dumpfile missing: " . $global['portable.db_dump_file'] . PHP_EOL , FILE_APPEND);
}
