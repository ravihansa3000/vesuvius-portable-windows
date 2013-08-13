<?php

/**
 * @name         Generate SSL Certificates
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0813
 */
require_once(realpath(dirname(__FILE__) . '/../../') . '/PortableApps/SahanaFoundation.org/www/local/lib/main.inc.php');
require_once(realpath(dirname(__FILE__) . '/../../') . '/PortableApps/SahanaFoundation.org/www/local/lib/lib_uuid.php');
require_once (realpath(dirname(__FILE__) . '/../../') . "/PortableApps/SahanaFoundation.org/unicon/main/includes/config.inc.php");
require_once (realpath(dirname(__FILE__) . '/../../') . "/PortableApps/SahanaFoundation.org/unicon/main/includes/functions.php");

$output_file = realpath(dirname(__FILE__) . '/../logs') . '/ssl_cert_error_log.txt';
if (file_exists($output_file)) {
    unlink($output_file);
}

/* Generate SSL self-signed certificate on machine move */
if (!isUUIDMatch()) {
    $success = true;
    run_location_tracker();   // Have servers moved if moved update configuration accordingly							  
    chdir(dirname(__FILE__) . "/../openssl"); // Change wd to this files location
    // Check folders exist if not create
    $crt_folder = US_USR . "/local/apache2/server_certs";
    if (!is_dir($crt_folder)) {
        mkdir($crt_folder) or file_put_contents($output_file, "Failed to create destination folder" . PHP_EOL, FILE_APPEND);
    }
    // Set environmental variables needed by openssl
    putenv("OPENSSL_CONF=.\openssl.cnf");
    putenv("RANDFILE=.rnd");
    // Create a new RSA private key
    exec('openssl.exe req -newkey rsa:2048 -batch -nodes -out vesuvius-www.csr -keyout vesuvius-www.key -subj "/C=US/ST=NY/L=Brooklyn/O=Sahana Software Foundation/emailAddress=admin@sahanafoundation.org/CN=vesuvius-www" ', $output, $return_var);
    if ($return_var !== 0) {
        file_put_contents($output_file, "openssl returned error: " . $return_var . "::: " . serialize($output) . PHP_EOL, FILE_APPEND);
        $success = false;
    }
    // Create a self-signed certificate
    exec('openssl.exe x509 -in vesuvius-www.csr -out vesuvius-www.crt -req -signkey vesuvius-www.key -days 3650', $output, $return_var);
    if ($return_var !== 0) {
        file_put_contents($output_file, "openssl returned error: " . $return_var . ":::" . serialize($output) . PHP_EOL, FILE_APPEND);
        $success = false;
    }
    putenv("OPENSSL_CONF=");
    unlink("vesuvius-www.csr");

    if (file_exists($output_file) && file_get_contents($output_file) === "") {
        unlink($output_file);
    }

    if ($success) {
        // Copy files to server
        copy('vesuvius-www.crt', US_APACHE . "/server_certs/vesuvius-www.crt");
        copy('vesuvius-www.key', US_APACHE . "/server_certs/vesuvius-www.key");

        // Delete files
        unlink('vesuvius-www.crt');
        unlink('vesuvius-www.key');
        unlink('.rnd');
    }
}