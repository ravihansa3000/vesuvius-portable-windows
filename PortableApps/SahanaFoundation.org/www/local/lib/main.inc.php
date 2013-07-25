<?php
/**
 * @name         Vesuvius Portable main include
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0715
 */
 

define("STATE_MACHINEMOVE", 2);
define("STATE_READY", 1);
define("STATE_NEW", 0);
define("STATE_ERROR", -1);
define("STATE_INVALID_INPUT", -2);
 
$global['portable.approot']  = realpath(dirname(__FILE__).'/../');
$global['sahana.approot']  = realpath(dirname(__FILE__).'/../../vesuvius/');
$global['portable.tmp_dir']  = realpath(dirname(__FILE__).'/../tmp/');
$global['portable.openssl_dir']  = realpath(dirname(__FILE__).'/../../../../../bin/openssl');

$global['portable.mysqldump.exe']  = $global['portable.approot'].'/../../usr/local/mysql/bin/mysqldump.exe';
$global['portable.mysql.exe']  = $global['portable.approot'].'/../../usr/local/mysql/bin/mysql.exe';

$global['portable.host'] = "localhost";
$global['portable.dbuser'] = "sahana";
$global['portable.dbport'] = "3306";
$global['portable.dbname'] = "sahana";
$global['portable.dbpasswd'] = "sahana@Portable";
$global['portable.title'] = "Vesuvius Portable";

$global['portable.state'] = STATE_NEW;
$global['portable.host_entry_tag'] = "   # Vesuvius Portable base_uuid entry";
$global['portable.win_host_file'] = getenv('systemroot').'/system32/drivers/etc/hosts';

$global['sahana.conf_file'] = $global['sahana.approot'].'/conf/sahana.conf';
$global['portable.conf_file'] = $global['portable.approot'].'/conf/portable.xml';
$global['portable.host_uuid_file'] = $global['portable.approot'].'/conf/host_uuid';
$global['portable.db_dump_file'] = $global['portable.approot'].'/db_dump/vesuvius-portable-db-dump.sql';

$pear_path = dirname(__FILE__) . '/PEAR';
set_include_path(get_include_path() . PATH_SEPARATOR . $pear_path);
error_reporting(E_ALL & ~E_DEPRECATED);