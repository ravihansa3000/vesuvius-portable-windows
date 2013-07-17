<?php
/**
 * @name         Vesuvius Portable Manager
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */

require_once(realpath(dirname(__FILE__).'/../').'/lib/main.inc.php');
require_once($global['portable.approot'].'/lib/clean_host.php');
require_once($global['portable.approot'].'/lib/lib_uuid.php');
require_once($global['portable.approot'].'/lib/lib_validate.php');
require_once($global['portable.approot'].'/lib/lib_portableconfig.php');
require_once($global['portable.approot']."/www/theme/head.php");
require_once($global['portable.approot']."/www/theme/body.php");
require_once($global['portable.approot']."/www/theme/footer.php");

/*******  Block access to Vesuvius from external devices if not registered by the owner *******/
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1'){
	if (file_exists($global['portable.conf_file']) && isUUIDMatch()){
		$host_entry = get_host_entry();
		header('Location: http://'.$host_entry);
	}else{
		shn_theme_head();		
		shn_theme_body_block();	
		shn_theme_footer();
		exit();
	}
}
/*************************** End Access Control ************************************/


/************ Action requests from external modules ************/
if (isset($_GET['action']) && $_GET['action'] === "CHECK_VESUVIUS_STATUS"){	
	echo "RUNNING";
	exit();
}
/************ End Action requests ******************************/


$err_list = array(); // error list container

// Step 1 - Validate sahana.conf file
if (!file_exists($global['sahana.conf_file'])){
	$global['portable.state'] = STATE_ERROR; 
	array_push($err_list, "sahana.conf file is missing! Please download a fresh copy and try again.");
}else{
	require_once($global['sahana.conf_file']); // we need this to get default parent base_uuid value
	if (trim($conf['base_uuid']) === '' || trim($conf['base_uuid']) === '/' || substr($conf['base_uuid'], -1) !== '/'){
		$global['portable.state'] = STATE_ERROR; 
		array_push($err_list, "Invalid sahana.conf file! Please download a fresh copy and try again.");
	}else{
		$global['sahana.base_uuid'] = $conf['base_uuid'];
	}	
}

// Step 2 - Check whether host_uuid file exists
if (!file_exists($global['portable.host_uuid_file'])){
	$global['portable.state'] = STATE_ERROR; 
	array_push($err_list, "host_uuid file is missing! Please download a fresh copy and try again.");
}

// Step 3 - Check whether Vesuvius Portable is registered by the owner
if ($global['portable.state'] !==  STATE_ERROR && isUUIDMatch() ){
	if (!file_exists($global['portable.conf_file'])){ // portable configuration file must exist
		$global['portable.state'] = STATE_ERROR; 
		array_push($err_list, "portable.xml file is missing! Please download a fresh copy and try again.");
	}else{
		$global['portable.state'] = STATE_READY;
		setupWinHostsFile($err_list); // Add mapping from base_uuid domain to loopback IP in Windows hosts file
		if ($global['portable.state'] !==  STATE_ERROR){
			$host_entry = get_host_entry(); // Get Vesuvius base_uuid
			header('Location: http://'.$host_entry); // redirect to Vesuvius home page
		}
	}
}else if ($global['portable.state'] !==  STATE_ERROR){ // Machine UUID has been changed from last recorded
	$global['portable.state'] = STATE_MACHINEMOVE;
}

// Step 4 - Prompt the owner to register this instance
if ($global['portable.state'] ===  STATE_MACHINEMOVE && isset($_POST['submit']) ){ // registration form is submitted
	$res = validateRegistrationForm(); // Validate user input
	if ($res === true){
		$form_data = populateFormData(); // Get validated user submitted form data from $_POST
		if (!create_portable_conf($form_data)){ // Create portable configuration file
			$global['portable.state'] = STATE_ERROR;
			array_push($err_list, "Could not create portable configuration! Please download a fresh copy and try again.");
		}
		if (!setup_sahana_conf()){ // Modify sahana.conf with new base_uuid and server parameters
			$global['portable.state'] = STATE_ERROR;
			array_push($err_list, "Could not setup Sahana configuration! Please download a fresh copy and try again.");
		}		
		setupWinHostsFile($err_list); // Add mapping from base_uuid domain to loopback IP in Windows hosts file
		createVesuviusAdminAccount($form_data, $err_list); // Create an admin account in Vesuvius for the owner
		if ($global['portable.state'] !==  STATE_ERROR){			
			createHostUUID();
			$host_entry = get_host_entry();			
			header('Location: http://'.$host_entry);
		}
	}else{ // user input validation failed, prompt user with invalid fields
		$global['portable.state'] = STATE_INVALID_INPUT;		
		shn_theme_head();		
		shn_theme_body_register_form_errors($res);
	}
}else if ($global['portable.state'] ===  STATE_MACHINEMOVE){	// prompt the user with registration form
	shn_theme_head();	
	shn_theme_body_register_form();	
}

// Something has gone wrong, display encountered error messages
if ($global['portable.state'] ===  STATE_ERROR){	
	shn_theme_head();	
	shn_theme_body_error($err_list);
}