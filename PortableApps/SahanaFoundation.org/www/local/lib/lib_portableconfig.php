<?php

/**
 * @name         Vesuvius Portable portable configuration
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */
require_once ('Beautifier.php');
require_once ('Beautifier/Batch.php');

/* Format sahana.conf php script for processing */
function beautify_phpfile($file){
	try{
		// Create the instance
		$oBeautifier = new PHP_Beautifier(); 
		// Add a filter, without any parameter
		$oBeautifier->addFilter('ArrayNested');	
		$oBeautifier->addFilter('IndentStyles');
		
		// Set the indent char, number of chars to indent and newline char
		$oBeautifier->setIndentChar(' ');
		$oBeautifier->setIndentNumber(4);
		$oBeautifier->setNewLine(PHP_EOL);
		// Define the input file
		$oBeautifier->setInputFile($file); 
		// Define an output file.
		$oBeautifier->setOutputFile($file . '.out'); 
		// Process the file. DON'T FORGET TO USE IT
		if (!$oBeautifier->process()){
			return false;
		}
		// Save the file
		$oBeautifier->save();
	}catch(Exception $oExp) {
		return false;
	}	
	unlink($file);
	rename($file . '.out', $file);	
	return true;
}

/* Set new values for sahana.conf variables */
function setup_sahana_conf(){
	global $global;
	$host_entry = get_host_entry();
	
	if ($host_entry === false){
		return false;
	}
	$base_uuid = $host_entry . "/";
	
	if (!beautify_phpfile($global['sahana.conf_file'])){
		return false;
	}
	$tmp_sahana_conf_file = $global['portable.tmp_dir'] . '/tmp_sahana.conf';
	copy($global['sahana.conf_file'], $tmp_sahana_conf_file);
	$sh = fopen($tmp_sahana_conf_file, 'r');
	$th = fopen($global['sahana.conf_file'], 'w');
	if (!($sh && $th)){
		return false;
	}
	
	while (($buffer = fgets($sh)) !== false) {
		$buffer = trim($buffer);
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'base_uuid'") !== false) {
			$buffer = '$conf[\'base_uuid\'] = "'. $base_uuid .'";' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'db_name'") !== false) {
			$buffer = '$conf[\'db_name\'] = "'. $global['portable.dbname'] .'";' . PHP_EOL;
		}	
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'db_host'") !== false) {
			$buffer = '$conf[\'db_host\'] = "'. $global['portable.host'] .'";' . PHP_EOL;
		}	
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'db_port'") !== false) {
			$buffer = '$conf[\'db_port\'] = "'. $global['portable.dbport'] .'";' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'db_user'") !== false) {
			$buffer = '$conf[\'db_user\'] = "'. $global['portable.dbuser'] .'";' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'db_pass'") !== false) {
			$buffer = '$conf[\'db_pass\'] = "'. $global['portable.dbpasswd'] .'";' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'db_engine'") !== false) {
			$buffer = '$conf[\'db_engine\'] = "mysql";' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'storage_engine'") !== false) {
			$buffer = '$conf[\'storage_engine\'] = "innodb";' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'dbal_lib_name'") !== false) {
			$buffer = '$conf[\'dbal_lib_name\'] = "adodb";' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'enable_monitor_sql'") !== false) {
			$buffer = '$conf[\'enable_monitor_sql\'] = false;' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'default_module'") !== false) {
			$buffer = '$conf[\'default_module\'] = \'home\';' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'default_action'") !== false) {
			$buffer = '$conf[\'default_action\'] = \'default\';' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'default_module_event'") !== false) {
			$buffer = '$conf[\'default_module_event\'] = \'inw\';' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'default_action_event'") !== false) {
			$buffer = '$conf[\'default_action_event\'] = \'default\';' . PHP_EOL;
		}
		if (substr($buffer,0,1) !== '#' && substr($buffer,0,1) !== '/' && strpos($buffer, "'enable_solr_for_search'") !== false) {
			$buffer = '$conf[\'enable_solr_for_search\'] = false;' . PHP_EOL;
		}
		fwrite($th, $buffer . PHP_EOL);
	}
	fclose($sh);
	fclose($th);
	unlink($tmp_sahana_conf_file);
	return true;
}

/*  Add host_entry to Windows hosts file 
	This will append a new line to Windows hosts file with a mapping from
	host_entry to 127.0.0.1 (loopback IP) This allows users to access Vesuvius 
	from base_uuid URL.
	*** Note - Since Apache virtual host for Vesuvius web app is the default 
			   virtual host, Vesuvius can be accessed through loopback IP
*/
function setupWinHostsFile(&$err_list){
	global $global;
	$host_entry = get_host_entry();
	if ($host_entry === false){
		$global['portable.state'] = STATE_ERROR;
		array_push($err_list, "Could not retrieve host entry! Please download a fresh copy and try again.");
	}else{
		clean_host();
		$win_host_file = $global['portable.win_host_file'];
		
		$lines = file($win_host_file);
		$last_line = $lines[count($lines)-1];
		
		if  (trim($last_line) === ""){
			$line = "127.0.0.1     " . $host_entry . $global['portable.host_entry_tag'] . PHP_EOL;
		}else{
			$line = PHP_EOL . "127.0.0.1     " . $host_entry . $global['portable.host_entry_tag'] . PHP_EOL;
		}	
		if (!file_put_contents($win_host_file, $line, FILE_APPEND)){
			$global['portable.state'] = STATE_ERROR;
			array_push($err_list, "Could not add entries to Windows hosts file!");
		}	
	}
}


/* Return value: Vesuvius base_uuid
   **Note - Vesuvius base_uuid is generated by appending portable instance ID to parent instance's base_uuid	
 */
function get_host_entry(){
	global $global;
	if (!file_exists($global['portable.conf_file'])){
		return false;	
	}
	$doc = new DOMDocument(); 
	$doc->load($global['portable.conf_file']);	
			
	$portable_base_uuids = $doc->getElementsByTagName("portable_base_uuid"); 
	$portable_base_uuid = $portable_base_uuids->item(0)->nodeValue;
	
	$host_entry = substr($portable_base_uuid, 0, -1);
	return $host_entry;
}

/* Return value - Array with portable configuration data if it exists 
				  False if portable configuration file is missing
*/
function get_portable_conf_data(){
	global $global;
	if (!file_exists($global['portable.conf_file'])){
		return false;	
	}
	$doc = new DOMDocument(); 
	$doc->load($global['portable.conf_file']);	
	
	$owner_names = $doc->getElementsByTagName("owner_name"); 
	$owner_name = $owner_names->item(0)->nodeValue; 

	$portable_ids = $doc->getElementsByTagName("portable_id"); 
	$portable_id = $portable_ids->item(0)->nodeValue; 

	$organizations = $doc->getElementsByTagName("organization"); 
	$organization = $organizations->item(0)->nodeValue; 
	
	$parent_base_uuids = $doc->getElementsByTagName("parent_base_uuid"); 
	$parent_base_uuid = $parent_base_uuids->item(0)->nodeValue;
	
	$owner_emails = $doc->getElementsByTagName("owner_email"); 
	$owner_email = $owner_emails->item(0)->nodeValue;	
	
	$portable_base_uuids = $doc->getElementsByTagName("portable_base_uuid"); 
	$portable_base_uuid = $portable_base_uuids->item(0)->nodeValue;
	
	$portable_conf_data = array( 
		'owner_name' => $owner_name, 
		'portable_id' => $portable_id, 
		'organization' => $organization,
		'parent_base_uuid' => $parent_base_uuid,
		'owner_email' => $owner_email,
		'portable_base_uuid' => $portable_base_uuid
	);
	return $portable_conf_data;
}


/* Create portable configuration file which contains Vesuvius Portable registration information */
function create_portable_conf($form_data){
	global $global;
	
	$owner_name_txt = $form_data['owner_name'];
	$portable_id_txt = $form_data['portable_id'];
	$organization_txt = $form_data['organization'];	
	$parent_base_uuid_txt = $form_data['parent_base_uuid'];
	$owner_email_txt = $form_data['owner_email'];
	$portable_base_uuid_txt = $form_data['portable_base_uuid'];
	
	$doc = new DOMDocument(); 
	$doc->formatOutput = true; 

	$r = $doc->createElement( "vesuvius-portable" ); 
	$doc->appendChild( $r ); 
	 
	$owner_name = $doc->createElement( "owner_name" );		
	$owner_name->appendChild( $doc->createTextNode($owner_name_txt) ); 
	$r->appendChild( $owner_name ); 
	
	$portable_id = $doc->createElement( "portable_id" );		
	$portable_id->appendChild( $doc->createTextNode($portable_id_txt) ); 
	$r->appendChild( $portable_id ); 
	
	$organization = $doc->createElement( "organization" );		
	$organization->appendChild( $doc->createTextNode($organization_txt) ); 
	$r->appendChild( $organization );
	
	$parent_base_uuid = $doc->createElement( "parent_base_uuid" );		
	$parent_base_uuid->appendChild( $doc->createTextNode($parent_base_uuid_txt) ); 
	$r->appendChild( $parent_base_uuid );
	
	$owner_email = $doc->createElement( "owner_email" );		
	$owner_email->appendChild( $doc->createTextNode($owner_email_txt) ); 
	$r->appendChild( $owner_email );
	
	$portable_base_uuid = $doc->createElement( "portable_base_uuid" );		
	$portable_base_uuid->appendChild( $doc->createTextNode($portable_base_uuid_txt) ); 
	$r->appendChild( $portable_base_uuid );
		
	$res = $doc->save($global['portable.conf_file']);
	if ($res === false){
		return false;
	}else{
		return true;
	}
}

/* Return value - Random alpha-numerical string of length 5 */
function get_rand_portable_id(){
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < 5; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;	
}