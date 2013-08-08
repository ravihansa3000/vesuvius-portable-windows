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
	
	// validate portable_desc field
	if (isset($_POST['portable_desc'])){
		$portable_desc = htmlspecialchars(trim($_POST['portable_desc']));
		if (strlen($portable_desc) > 50 ){
			$validation_passed = false;
			array_push($invalid_fields, "portable_desc");
		}
	}else{
		$validation_passed = false;
		array_push($invalid_fields, "portable_desc");
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
	
	// validate user_name field
	if (isset($_POST['user_name'])){
		$user_name = htmlspecialchars(trim($_POST['user_name']));
		if (preg_match('/[^A-Za-z0-9]/', $user_name)  || strlen($user_name) < 5  || strlen($user_name) > 15 ){
			$validation_passed = false;
			array_push($invalid_fields, "user_name");
		}
	}else{
		$validation_passed = false;
		array_push($invalid_fields, "user_name");
	}
	
	// validate password field
	if (isset($_POST['password']) && isset($_POST['password_re'])){
		$password = htmlspecialchars(trim($_POST['password']));
		$password_re = htmlspecialchars(trim($_POST['password_re']));
		if ($password !== $password_re || preg_match('/[^A-Za-z0-9]/', $password)  || strlen($password) < 8  || strlen($password) > 15 ){
			$validation_passed = false;
			array_push($invalid_fields, "password");
			array_push($invalid_fields, "password_re");
		}
	}else{
		$validation_passed = false;
		array_push($invalid_fields, "password");
		array_push($invalid_fields, "password_re");
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
	$portable_desc = htmlspecialchars(trim($_POST['portable_desc']));
	$organization = htmlspecialchars(trim($_POST['organization']));
	$parent_base_uuid = htmlspecialchars(trim($_POST['parent_base_uuid']));	
	$owner_email = htmlspecialchars(trim($_POST['owner_email']));
	$user_name = htmlspecialchars(trim($_POST['user_name']));
	$password = htmlspecialchars(trim($_POST['password']));
	
	$portable_base_uuid = php_uname("n") . "/"; // get Windows machine name
	
	$form_data = array(
		'owner_name' => $owner_name, 
		'portable_desc' => $portable_desc, 
		'organization' => $organization,
		'parent_base_uuid' => $parent_base_uuid,
		'owner_email' => $owner_email,
		'portable_base_uuid' => $portable_base_uuid,
		'user_name' => $user_name,
		'password' => $password
	);
	return $form_data;
}