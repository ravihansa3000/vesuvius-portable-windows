<?php

/**
 * @name         Wireless Network Handler
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */

function getNICList(){
	$obj = new COM('winmgmts://localhost/root/CIMV2');	
	$items = $obj->ExecQuery("SELECT * from Win32_NetworkAdapter where Manufacturer <> 'Microsoft' AND MACAddress <> null AND  PhysicalAdapter=True");
	
	$nic_list = array();
	
	foreach ( $items as $item ){		
        
		$nic_item = array(
		   'MACAddress' => $item->MACAddress,
		   'Description' => $item->Description,
		   'PhysicalAdapter' => $item->PhysicalAdapter,
		   'AdapterType' => $item->AdapterType
		);
		
		array_push($nic_list, $nic_item);
    }	
	return $nic_list;
}