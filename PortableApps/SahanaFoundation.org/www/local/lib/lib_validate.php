<?php

/**
 * @name         Vesuvius Portable portable configuration
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */
 
 /* Validate registration user input form 
	Return: True - on successful validation
			Array - on invalid user input with invalid fields
 */
 function validateRegistrationForm(){
	$invalid_fields = array(); // validation error list container
	$validation_passed = true;	
	
	// validate owner_name field
	if (isset($_POST['owner_name'])){
		$owner_name = htmlspecialchars(trim($_POST['owner_name']));
		if (preg_match('/[^A-Za-z0-9\-\.\s]/', $owner_name) || strlen($owner_name) < 4  || strlen($owner_name) > 30 ){
			// string contains only english letters & digits
			$validation_passed = false;
			array_push($invalid_fields, "owner_name");
		}
	}else{
		$validation_passed = false;
		array_push($invalid_fields, "owner_name");
	}
	
	// validate portable_id field
	if (isset($_POST['portable_id'])){
		$portable_id = htmlspecialchars(trim($_POST['portable_id']));
		if (preg_match('/[^A-Za-z0-9]/', $portable_id)  || strlen($portable_id) < 5  || strlen($portable_id) > 8 ){
			$validation_passed = false;
			array_push($invalid_fields, "portable_id");
		}
	}else{
		$validation_passed = false;
		array_push($invalid_fields, "portable_id");
	}
	
	// validate organization field
	if (isset($_POST['organization'])){
		$organization = htmlspecialchars(trim($_POST['organization']));
		if (preg_match('/[^A-Za-z0-9\-\.\s!@\(\)]/', $organization) || strlen($organization) < 3  || strlen($organization) > 30){
			$validation_passed = false;
			array_push($invalid_fields, "organization");
		}
	}else{
		$validation_passed = false;
		array_push($invalid_fields, "organization");
	}
	
	// validate parent_base_uuid field
	if (isset($_POST['parent_base_uuid'])){
		$parent_base_uuid = htmlspecialchars(trim($_POST['parent_base_uuid']));	
		//  base_uuid must end with a trailing slash	
		if (substr($parent_base_uuid, -1) !== '/' || preg_match('/[^A-Za-z0-9\-\._~\/\(\)@!\$&:\+=%\']/', $parent_base_uuid) || strlen($parent_base_uuid) < 3){	
			$validation_passed = false;
			array_push($invalid_fields, "parent_base_uuid");
		}
		
		/* The domain column in base_uuid
				- length must range between 3 and 63 characters (excluding the extension)
				- can neither start nor end with the character "-" although the character "-" is allowed inside the name
				- can use any letter, numbers between 0 and 9, and the symbol "-"
				- names 'localhost' and 'vesuvius' are reserved and not allowed
		*/
		$arr = explode("/", $parent_base_uuid, 2);
		$domain_name = strtolower($arr[0]);
		if ($domain_name === "localhost" || $domain_name === "vesuvius"){
			$validation_passed = false;
			array_push($invalid_fields, "parent_base_uuid");
		}
		if (!preg_match('/^([a-z0-9]([a-z0-9\-]{0,61}[a-z0-9])?\.)+[a-z]{2,6}$/', $domain_name)){
			$validation_passed = false;
			array_push($invalid_fields, "parent_base_uuid");
		}
	}else{
		$validation_passed = false;
		array_push($invalid_fields, "parent_base_uuid");
	}
	
	// validate owner_email field
	if (isset($_POST['owner_email'])){
		$owner_email = htmlspecialchars(trim($_POST['owner_email']));
		if (!filter_var($owner_email, FILTER_VALIDATE_EMAIL)){
			$validation_passed = false;
			array_push($invalid_fields, "owner_email");
		}
	}else{
		$validation_passed = false;
		array_push($invalid_fields, "owner_email");
	}
 
	if ($validation_passed){
		return true;
	}else{
		return $invalid_fields;
	}
 }
 
 /* Get validated user submitted registration data */
function populateFormData(){	
	$owner_name = htmlspecialchars(trim($_POST['owner_name']));
	$portable_id = htmlspecialchars(trim($_POST['portable_id']));
	$organization = htmlspecialchars(trim($_POST['organization']));
	$parent_base_uuid = htmlspecialchars(trim($_POST['parent_base_uuid']));	
	$owner_email = htmlspecialchars(trim($_POST['owner_email']));
	
	$arr = explode("/", $parent_base_uuid, 2);
	$domain_name = $arr[0];
	$portable_base_uuid = $domain_name . "." . $portable_id . "/";
	
	$form_data = array(
		'owner_name' => $owner_name, 
		'portable_id' => $portable_id, 
		'organization' => $organization,
		'parent_base_uuid' => $parent_base_uuid,
		'owner_email' => $owner_email,
		'portable_base_uuid' => $portable_base_uuid
	);
	return $form_data;
}