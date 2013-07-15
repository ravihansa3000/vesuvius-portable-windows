<?php
/**
 * @name         Re-setup Vesuvius
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.06.10
 */

require_once(realpath(dirname(__FILE__).'/../../').'/PortableApps/SahanaFoundation.org/www/local/lib/main.inc.php');

file_put_contents($global['portable.host_uuid_file'], "0");