<?php
/**
 * @name         Clear Logs
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */
 
 
$PHP_LOG = realpath(dirname(__FILE__) . '/../../PortableApps/SahanaFoundation.org/usr/local/php').'/php_errors.log';
$APACHE_ERROR_LOG = realpath(dirname(__FILE__) . '/../../PortableApps/SahanaFoundation.org/usr/local/apache2/logs').'/error.log';
$APACHE_SSL_ERROR_LOG = realpath(dirname(__FILE__) . '/../../PortableApps/SahanaFoundation.org/usr/local/apache2/logs').'/error_ssl.log';
$APACHE_ACCESS_LOG = realpath(dirname(__FILE__) . '/../../PortableApps/SahanaFoundation.org/usr/local/apache2/logs').'/access.log';
$APACHE_SSL_ACCESS_LOG = realpath(dirname(__FILE__) . '/../../PortableApps/SahanaFoundation.org/usr/local/apache2/logs').'/access_ssl.log';
$PHP_XDEBUG = realpath(dirname(__FILE__) . '/../../PortableApps/SahanaFoundation.org/usr/local/php/xdebug');
 
file_put_contents($PHP_LOG, "");
file_put_contents($APACHE_ERROR_LOG, "");
file_put_contents($APACHE_SSL_ERROR_LOG, "");
file_put_contents($APACHE_ACCESS_LOG, "");
file_put_contents($APACHE_SSL_ACCESS_LOG, "");

$xdebug_dir = new DirectoryIterator($PHP_XDEBUG);
foreach ($xdebug_dir as $fileinfo) {
    if (!$fileinfo->isDot()) {
        unlink($fileinfo->getPathname());
    }
}