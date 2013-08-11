<?php

/**
 * @name         Build Portable Wrapper Archive
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine and the Sahana Foundation
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0811
 */
require_once(realpath(dirname(__FILE__) . '/../') . '/PortableApps/SahanaFoundation.org/www/local/lib/main.inc.php');
ini_set('memory_limit', '512M');
$base_dir = realpath(dirname(__FILE__) . '/../');
$DIR_FILTERS = array();
$FILE_FILTERS = array();

$DIR_FILTERS[] = realpath($base_dir . '/.bzr');
$DIR_FILTERS[] = realpath($base_dir . '/build');
$DIR_FILTERS[] = realpath($base_dir . '/PortableApps/SahanaFoundation.org/www/vesuvius');

$FILE_FILTERS[] = realpath($base_dir . '/.bzrignore');
$FILE_FILTERS[] = realpath($global['portable.conf_file']);
$FILE_FILTERS[] = realpath($global['portable.host_uuid_file']);
$FILE_FILTERS[] = realpath($global['portable.db_dump_file']);

// Clean log files
require_once(realpath(dirname(__FILE__) . '/../') . '/bin/php_scripts/clear-logs.php');

if ($argv[1] === "gz") {
    $dest_file = dirname(__FILE__) . '/portable-wrapper_win.tar';
    @unlink($dest_file);
    @unlink($dest_file . '.gz');
    $phar = new PharData($dest_file);
    $dir_itr = new RecursiveDirectoryIterator($base_dir);
    $itr = new RecursiveIteratorIterator(new MyRecursiveFilterIterator($dir_itr));
    $phar->buildFromIterator($itr, $base_dir);
    $phar = $phar->compress(Phar::GZ);
    @unlink($dest_file);
} else if ($argv[1] === "zip") {
    $dest_file = dirname(__FILE__) . '/portable-wrapper_win.zip';
    @unlink($dest_file);
    $zip = new ZipArchive();
    $zip->open($dest_file, ZipArchive::CREATE);
    zip_add_dir($base_dir . '/', $zip);
    $zip->close();
} else {
    echo "No method specified";
    exit;
}

function zip_add_dir($dir, $dpa_zip) {
    global $DIR_FILTERS, $base_dir, $FILE_FILTERS;
    if (!is_dir($dir)) {
        return;
    }
    $dh = opendir($dir);
    if (!$dh) {
        return;
    }

    // Loop through all the files
    while (($file = readdir($dh)) !== false) {
        $item = realpath($dir . $file);
        echo $item . PHP_EOL;
        //If it's a folder, run the function again!
        if (!is_file($dir . $file)) {
            // Skip parent and root directories
            if (($file !== '.') && ($file !== '..')) {
                // Skip unselected modules
                if (!in_array($item, $DIR_FILTERS)) {
                    // Recursive call to add subdirectories        
                    $dpa_zip->addEmptyDir(str_replace($base_dir . '\\', '', $item));
                    zip_add_dir($dir . $file . '/', $dpa_zip);
                }
            }
        } else {
            // Add files
            if (!in_array($item, $FILE_FILTERS)) {
                $dpa_zip->addFile($item, str_replace($base_dir . '\\', '', $item));
            }
        }
    }
}

class MyRecursiveFilterIterator extends RecursiveFilterIterator {

    public static $DIR_FILTERS = array();

    public function __construct(RecursiveIterator $i) {
        parent::__construct($i);
    }

    public function accept() {
        global $DIR_FILTERS;
        $filepath = realpath($this->current()->getPath());
        echo $filepath . PHP_EOL;
        if (in_array($filepath, $DIR_FILTERS)) {
            return false;
        }
        return true;
    }

}

?>