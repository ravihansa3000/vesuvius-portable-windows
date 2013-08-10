<?php
/**
 * @name         Vesuvius Portable remote clients redirect
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0809
 */
require_once(realpath(dirname(__FILE__) . '/../') . '/lib/main.inc.php');
require_once(realpath(dirname(__FILE__) . '/../') . '/lib/lib_uuid.php');

// First check whether Vesuvius Portable is registered
if (file_exists($global['sahana.conf_file']) && file_exists($global['portable.conf_file']) && file_exists($global['portable.host_uuid_file'])) {
    if (!defined('LC_MESSAGES'))
        define('LC_MESSAGES', 6);
    require_once(realpath(dirname(__FILE__) . '/../../') . '/vesuvius/www/index.php');
}else {
    // Vesuvius Portable is not yet registered, display the blocked message
    ?>
    <h2>Vesuvius Portable has not been registered. Please inform the instance owner.</h2><br/><br/>
    <h3>If you are the owner please follow <a href="http://localhost">this link</a></h3>
    <?
}?>