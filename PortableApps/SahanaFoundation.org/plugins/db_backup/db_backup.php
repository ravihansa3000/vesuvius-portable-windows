<?php
/*
###############################################################################
# Name: The Uniform Server MySQL Backup v 1.0
# Developed By: The Uniform Server Development Team
# Modified Last By: Mike Gleaves (Ric) 
# Web: http://www.uniformserver.com
# V 1.0
# V 1.1 15-2-2010 - Added \r\n to keep notepad happy.
#                  Now uses constants from unicon\main\includes\config.inc.php
#                  Note US_DB_HOST added  
###############################################################################
*/
//error_reporting(0); // Disable PHP errors and warnings
                      // Comment to Enable for testing

chdir(dirname(__FILE__)); // Change wd to this files location
include_once "../../unicon/main/includes/config.inc.php";
include_once "../../unicon/main/includes/functions.php";

run_location_tracker();  // Have servers moved if moved update configuration
print"\n";


//=== User can change =========================================================

$logging   = true;  // true = Enable logging false = disable logging

//=== End User change==========================================================


//=== Get Configuration
$file =  "db_backup.ini";

$ini_array   = parse_ini_file($file);      // Read db_backup.ini into array
$FIFO        = $ini_array['Fifo'];         // Number of archives to save 
$BACKUP_NAME = $ini_array['Archive_name'] . date('Y-m-d-H-i-s'); //File+time 
$EXCLUDES    = $ini_array['Excludes'];     // Assign array to exclude array

//=== Mysql connection settings
$MYSQL_HOST   = US_DB_HOST;                // Default on 3306 
$MYSQL_PORT   = get_mysql_port();          // Default 3306 User Changeable
$MYSQL_USER   = 'root';                    // root user can access all DBs
$MYSQL_PASSWD = get_mysql_password();      // Default root User Changeable

//== Check MySQL is running
if(!mysql_running()){                      // Is MySQL running
 print " Unable to run backup script \n";
 print " MySQL Server not running! \n\n";  // No: give up
 exit;
}

//=== Create folders if they do not exist
if(!is_dir(US_DB_BASE)) {
 mkdir(US_DB_BASE) or die("Failed to create main destination folder");
}
if(!is_dir(US_DB_DEST)) {
 mkdir(US_DB_DEST) or die("Failed to create destination sub-folder");
}

if(!is_dir(US_DB_TEMP)){
  mkdir(US_DB_TEMP) or die("Failed to create temp folder");
}

//=== Check FIFO Archived files in folder
//
$file_count=0;
if ($handle = opendir(US_DB_DEST)) {             // Get handle
  while (false !== ($file = readdir($handle))) { // Scan file
   if ($file != "." && $file != "..") {          // Exclude these
     $file_count +=1;                            // Count files
   }
  }
  closedir($handle);                             // Finished close
}

 $ref_tim = 9999999999;                            // Just to start filter
 if($file_count == $FIFO ){                        // Is max files in FIFO

  if ($handle = opendir(US_DB_DEST)) {                  // Yes: Get handle              
   while (false !== ($file = readdir($handle))) {  // Scan files 
    if ($file != "." && $file != "..") {           // exclude these
      $new_tim=filemtime(US_DB_DEST.'/'.$file);         // Unix Time stamp
      if($new_tim < $ref_tim){                     // Is time stamp smaller
        $ref_tim = $new_tim;                       // Yes: Set new ref
        $ref_file = US_DB_DEST.'/'.$file;               // Capture file  
      }
    }
   }//end while
   closedir($handle);
  }//if

  // Thats obtained the oldest file now delete it 
  unlink($ref_file);

  //  print "\n $ref_tim \n";// TEST
  //  echo "$ref_file\n";    // TEST

}// End if fifo


//=== Add entry to log file start header
db_log("============= LOG $BACKUP_NAME.7z");

//=== Dump and compress databases

// Loop through databases
 $MYSQL_HOST2 = $MYSQL_HOST.":".$MYSQL_PORT; // Add port number
 $db_conn   = @mysql_connect( $MYSQL_HOST2, $MYSQL_USER, $MYSQL_PASSWD ) or die("Problems Connecting to DB");
 $db_result = mysql_list_dbs($db_conn);
 $db_auth   = " --host=\"$MYSQL_HOST\" --port=\"$MYSQL_PORT\" --user=\"$MYSQL_USER\" --password=\"$MYSQL_PASSWD\"";

while ($db_row = mysql_fetch_object($db_result)) {
  $db = $db_row->Database;

  if(in_array($db, $EXCLUDES)){ // Is DB matched in array
   continue;                    // Yes: Exclude. Process next entery from DB
  }
  //  print " $db \n"; //=== Test line

  //=== Dump dbs to temp folder
  unset($output);                      // Clear output error buffer
  exec(US_MYSQL_BIN."/mysqldump $db_auth --opt $db 2>&1 >".US_DB_TEMP."/$db.sql", $output, $res);
  if( $res > 0 ) {                     // Was the dump OK
    db_log('Dump Failed     : '.$db);  // No: Log failed 
    die("Dump Failed: $output\n");     // inform user
  }
  else{
    db_log('Database dump OK: '.$db);  // Yes: og pass 
    print " Database dump OK: $db \n"; // inform user
  }
  //=== Compress using 7z
  unset($output);                    // Clear output error buffer
  exec( "7za.exe a -t7z ".US_DB_TEMP."/$db.sql.7z ".US_DB_TEMP."/$db.sql 2>&1" , $output, $res );

  if( $res > 0 ) {                      // Was compression OK 
    die("Compress Failed \n $output");  // No: Failed
  }
  else{
   print " Compressed    OK: $db \n";
  }
} // end while
mysql_free_result($db_result); // clean up
mysql_close($db_conn);         // close connection 

//=== Archive compressed databases using 7z

  unset($output);                    // Clear output error buffer
  exec( "7za.exe a -t7z ".US_DB_DEST."/$BACKUP_NAME.7z ".US_DB_TEMP."/*.7z 2>&1" , $output, $res );

  if( $res > 0 ) {                              // Was compression OK
    db_log("Archived Failed: $BACKUP_NAME.7z"); // No: Log failed  
    db_log(".............................................................\n");  
    die(" Archived Failed: \n $BACKUP_NAME.7z");  // No: Failed
  }
  else{
    db_log("Archived      OK: $BACKUP_NAME.7z");  //Yes: Log pass 
    db_log(".............................................................\n");  
    print " Archived      OK: $BACKUP_NAME.7z \n";
  }

//=== Remove files in temp folder
if ($dir = @opendir(US_DB_TEMP)) {
 while (($file = readdir($dir)) !== false) {
   if (!is_dir($file)) {
    unlink(US_DB_TEMP.'/'.$file);
   }
  }
}
closedir($dir);

//=== Delete temp folder
rmdir(US_DB_TEMP);

//=== Log =====================================================================
// Logs Database actions to a log file
// Input: String to be logged
// $file  Path to file including file name

function db_log($str){
 global$logging;         // Is logging enabled
 if($logging){
  $str = date('Y-m-d H:i')."  ".$str."\r\n";

  $fh = fopen(USF_DB_LOG, 'a') or die("can't open file");
  fwrite($fh, $str);
  fclose($fh);
 }
 
}
//=== END Log =================================================================

?>