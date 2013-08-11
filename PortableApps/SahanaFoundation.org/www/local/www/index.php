<?php

/**
 * @name         Vesuvius Portable Manager
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0811
 */
require_once(realpath(dirname(__FILE__) . '/../') . '/lib/main.inc.php');
require_once($global['portable.approot'] . '/lib/lib_uuid.php');
require_once($global['portable.approot'] . '/lib/lib_validate.php');
require_once($global['portable.approot'] . '/lib/lib_portableconfig.php');
require_once($global['portable.approot'] . "/www/theme/head.php");
require_once($global['portable.approot'] . "/www/theme/body.php");
require_once($global['portable.approot'] . "/www/theme/footer.php");

// Block access to Vesuvius from external devices if not registered by the owner
if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    if (file_exists($global['portable.conf_file'])) {
        $host_entry = get_win_machine_name();
        header('Location: http://' . $host_entry);
        exit();
    } else {
        shn_theme_head();
        shn_theme_body_block();
        exit();
    }
}

/********** Action requests from external modules ************/
if (isset($_GET['action']) && $_GET['action'] === "CHECK_VESUVIUS_STATUS") {
    echo "RUNNING";
    exit();
}
/********** End Action requests ******************************/

// Global error list container
$global['error_list'] = array();

// Validate sahana.conf file
if (!file_exists($global['sahana.conf_file'])) {
    add_error("sahana.conf file is missing! Please download a fresh copy and try again.");
} else {
    require_once($global['sahana.conf_file']);
    if (!isset($conf['base_uuid']) || trim($conf['base_uuid']) === '') {
        add_error("Invalid sahana.conf file! Please download a fresh copy and try again.");
    } else {
        $global['sahana.base_uuid'] = $conf['base_uuid'];
    }
}

// Check whether host_uuid file exists
if (!file_exists($global['portable.host_uuid_file'])) {
    add_error("host_uuid file is missing! Please download a fresh copy and try again.");
}

// Check whether Vesuvius Portable is registered by the owner
if ($global['portable.state'] !== STATE_ERROR && file_exists($global['portable.conf_file']) && isUUIDMatch()) {
    $global['portable.state'] = STATE_READY;
    $host_entry = get_win_machine_name();
    header('Location: http://' . $host_entry); // redirect to Vesuvius home page
    exit();
} else if ($global['portable.state'] !== STATE_ERROR) { // Machine UUID has been changed from last recorded
    $global['portable.state'] = STATE_MACHINEMOVE;
}

// Prompt the owner to register this instance
if ($global['portable.state'] === STATE_MACHINEMOVE && isset($_POST['submit'])) { // registration form is submitted
    $res = validateRegistrationForm(); // Validate user input
    if ($res === true) {
        // Get validated user submitted form data from $_POST
        $form_data = populateFormData();

        // Create portable configuration file
        create_portable_conf($form_data);

        // Modify sahana.conf with new base_uuid and server parameters
        setup_sahana_conf();

        // Modify .htaccess RewriteBase to '/'
        setup_vesuvius_htaccess();

        // Create an admin account in Vesuvius for the owner
        createVesuviusAdminAccount($form_data);

        if ($global['portable.state'] !== STATE_ERROR) {
            createHostUUID();
            $host_entry = get_win_machine_name();
            header('Location: http://' . $host_entry);
            exit();
        }
    } else { // user input validation failed, prompt user with invalid fields
        $global['portable.state'] = STATE_INVALID_INPUT;
        shn_theme_head();
        shn_theme_body_register_form($res);
    }
} else if ($global['portable.state'] === STATE_MACHINEMOVE && isset($_POST['skip']) && file_exists($global['portable.conf_file'])) {
    // If previous registration is found allow skipping registration
    setup_sahana_conf();
    if ($global['portable.state'] !== STATE_ERROR) {
        createHostUUID();
        $host_entry = get_win_machine_name();
        header('Location: http://' . $host_entry);
        exit();
    }
} else if ($global['portable.state'] === STATE_MACHINEMOVE) { // prompt the user with registration form
    shn_theme_head();
    shn_theme_body_register_form(false);
}

// Something has gone wrong, display encountered error messages
if ($global['portable.state'] === STATE_ERROR) {
    shn_theme_head();
    shn_theme_body_error();
}

function add_error($error) {
    global $global;
    $global['portable.state'] = STATE_ERROR;
    if (is_array($error)) {
        array_push($global['error_list'], $error);
    } else {
        $global['error_list'][] = $error;
    }
}