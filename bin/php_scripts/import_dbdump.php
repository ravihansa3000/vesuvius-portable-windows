<?php

/**
 * @name         Import Database Dump
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0810
 */
require_once(realpath(dirname(__FILE__) . '/../../') . '/PortableApps/SahanaFoundation.org/www/local/lib/main.inc.php');
chdir(dirname(__FILE__)); // Change wd to this files location

$output_file = realpath(dirname(__FILE__) . '/../logs') . '/import_error_log.txt';
$std_err_file = "..\logs\import_error_log.txt";
$dbdump_file = realpath($global['portable.db_dump_file']);
$dbdump_file_tmp = $dbdump_file . ".tmp";
$mysql_exec = '"' . $global['portable.mysql.bin'] . '\mysql.exe"';

if (file_exists($output_file)) {
    unlink($output_file);
}

// check whether this is the first run. If not don't bother with db import
if (file_exists($global['portable.host_uuid_file'])) {
    exit();
}

if (file_exists($dbdump_file)) { // db dump file must exist    
    /* Remove any DEFINER entries to avoid any MySQL permission issues */
    copy($dbdump_file, $dbdump_file_tmp);
    $pattern = "/DEFINER[ ]*=[ ]*[^*\s]*/i";
    $sh = fopen($dbdump_file_tmp, 'r');
    $th = fopen($dbdump_file, 'w');
    if ($sh && $th) {
        while (($buffer = fgets($sh)) !== false) {
            $new = preg_replace($pattern, "", $buffer);
            fwrite($th, $new);
        }
        fclose($sh);
        fclose($th);

        /* Drop the database if it already exists */
        exec($mysql_exec . ' --user=' . $global['portable.dbuser'] . ' --password=' . $global['portable.dbpasswd'] . ' --host=' . $global['portable.host'] . ' --port=' . $global['portable.dbport'] . ' --execute="DROP DATABASE IF EXISTS ' . $global['portable.dbname'] . ';" 2>>' . $std_err_file, $output, $return_var);

        if ($return_var !== 0) { // return value should be 0 on success
            file_put_contents($output_file, "Error: " . serialize($output) . PHP_EOL, FILE_APPEND);
        }

        /* Create a new database */
        exec($mysql_exec . ' --user=' . $global['portable.dbuser'] . ' --password=' . $global['portable.dbpasswd'] . ' --host=' . $global['portable.host'] . ' --port=' . $global['portable.dbport'] . ' --execute="CREATE DATABASE ' . $global['portable.dbname'] . ';" 2>>' . $std_err_file, $output, $return_var);

        if ($return_var !== 0) { // return value should be 0 on success
            file_put_contents($output_file, "Error: " . serialize($output) . PHP_EOL, FILE_APPEND);
        }

        /* Execute queries in database dump file */
        exec($mysql_exec . ' --user=' . $global['portable.dbuser'] . ' --password=' . $global['portable.dbpasswd'] . ' --host=' . $global['portable.host'] . ' --port=' . $global['portable.dbport'] . ' ' . $global['portable.dbname'] . ' < "' . $dbdump_file . '" 2>>' . $std_err_file, $output, $return_var);

        if ($return_var !== 0) { // return value should be 0 on success
            file_put_contents($output_file, "Error: " . serialize($output) . PHP_EOL, FILE_APPEND);
        }

        if (file_exists($output_file) && file_get_contents($output_file) === "") {
            unlink($output_file); // remove error log file if it is empty
        }
        unlink($dbdump_file_tmp);

        if (!file_exists($output_file)) {
            // remove dbdump only if db import is success
            rename($global['portable.db_dump_file'], $global['portable.db_dump_file'] . '.bak');

            // create host_uuid file with a dummy UUID, this will trigger a machine movement in Vesuvius Portable Manager
            file_put_contents($global['portable.host_uuid_file'], "0");
        }
    } else {
        file_put_contents($output_file, "Error reading file: " . $global['portable.db_dump_file'] . PHP_EOL, FILE_APPEND);
    }
} else {
    file_put_contents($output_file, "Database dumpfile missing: " . $global['portable.db_dump_file'] . PHP_EOL, FILE_APPEND);
}
