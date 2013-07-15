<?php

/**
 * @name         Windows Host Machine UUID Library
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */

 /* Return value - Windows machine specific UUID string */
function getSystemUUID(){
	$obj = new COM('winmgmts://localhost/root/CIMV2');	
	$items = $obj->ExecQuery("SELECT * from Win32_ComputerSystemProduct");
	
	foreach ( $items as $item ){
        $sys_uuid = $item->UUID;
    }	
	return $sys_uuid;
}

/* Create host_uuid file with Windows machine specific UUID value */
function createHostUUID(){
	global $global;
	file_put_contents($global['portable.host_uuid_file'], getSystemUUID());
}

/* Return value - True when current system UUID matches with last recorded UUID
				  False when current system UUID does not match with last recorded UUID
 */
function isUUIDMatch(){
	if (getSystemUUID() === getLastRecordedUUID()){
		return true;
	}else{
		false;
	}
}

/* Return value - File contents in host_uuid file
   ***Note - This file contains the last recorded system UUID value
 */
function getLastRecordedUUID(){
	global $global;
	if (!file_exists($global['portable.host_uuid_file'])){
		return -1;
	}	
	return file_get_contents($global['portable.host_uuid_file']);
}