<?php

/**
 * @name         Check Ports
 * @version      0.1
 * @author       Akila Ravihansa Perera <ravihansa3000@gmail.com>
 * @about        Developed in whole or part by the U.S. National Library of Medicine
 * @link         https://pl.nlm.nih.gov/about
 * @link         http://sahanafoundation.org
 * @license	 http://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License (LGPL)
 * @lastModified 2013.0808
 */
$output_file_conflicts = realpath(dirname(__FILE__) . '/../logs') . '/portconflicts.txt';
$output_file_running = realpath(dirname(__FILE__) . '/../logs') . '/running.txt';
if (file_exists($output_file_conflicts)) {
    unlink($output_file_conflicts);
}
if (file_exists($output_file_running)) {
    unlink($output_file_running);
}

/* First check whether Vesuvius Portable is already running */
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost/index.php?action=CHECK_VESUVIUS_STATUS");
curl_setopt($ch, CURLOPT_TIMEOUT, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$server_output = curl_exec($ch);
curl_close($ch);
if ($server_output && $server_output === "RUNNING") {
    file_put_contents($output_file_running, getmypid());
    exit();
}

/* Check for processes listening on ports 443, 80, 3306 */
$output = array();
$conflicts = array();
exec('netstat -nao | find /i "listening" ', $output, $return_var);

if ($return_var !== 0) {
    // Failed to execute netstat. Exit
    exit();
}

$hasConflicts = false;

foreach ($output as $line) {
    if (preg_match("/.*(:3306).*|.*(:80).*|.*(:443).*/", $line)) {
        echo $line . PHP_EOL;
        $hasConflicts = true;
        $pid = end(explode(' ', $line));
        array_push($conflicts, $pid);
    }
}

/* If there are any conflicts use WMI to get process names */
if ($hasConflicts) {
    $results = array();
    $unique_pids = array_unique($conflicts);
    $obj = new COM('winmgmts://localhost/root/CIMV2');
    array_push($results, "Process Name  \t\t Process ID");

    foreach ($unique_pids as $pid) {
        $items = $obj->ExecQuery("SELECT * from Win32_Process WHERE ProcessId=" . $pid);
        foreach ($items as $item) {
            array_push($results, $item->Name . " \t\t " . $pid);
        }
    }
    // log conflicting processes
    file_put_contents($output_file_conflicts, implode(PHP_EOL, $results));
} 