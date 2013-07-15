<?php
/*
#############################################################################
# Name: The Uniform Server Admin Panel
# Developed By: The Uniform Server Development Team
# Modified Last By: Olajide Olaolorun (empirex) Mike Gleaves (Ric) 
# Web: http://www.uniformserver.com
# Notes: Sets MySQL password.
#
# Resets cookie to keep phpMyAdmin happy after restarting server with browser still running
# Note: name must be phpMyAdmin value anything I am using FALSE path as set in original cookie
# setcookie('phpMyAdmin', 'FALSE', false, '/apanel/phpMyAdmin/', false, 0); // set cookie
#
# 23-3-2010 Both root hosts (localhost and 127.0.0.1) passwords now updated
#           US_DB_HOST uses "127.0.0.1" added FLUSH PRIVILEGES 
#  4-7-2011 Added ipv6 support for MySQL. Added password to pma
#############################################################################
*/

setcookie('phpMyAdmin', 'FALSE', true, '/apanel/phpMyAdmin/', false, 0); // reset cookie

// Includes
include_once "includes/config.inc.php";
include_once US_APANEL_WWW."/includes/lang/".file_get_contents(US_APANEL_WWW."/includes/.lang").".php";
include_once "includes/header.php";
include_once "includes/secure.php";
include_once USF_CON_FUNCTIONS;

if ( !(file_exists(USF_MYSQL_PASSWORD)) ) {     // Does file exist
    $FHandle = fopen(USF_MYSQL_PASSWORD, 'w');  // no: create it
    fclose($FHandle);
}

// Get MySQL port number
$filename=USF_MYSQL_INI;                               // mySQL config file
  if ($filearray=file($filename)) {                    // read file into array
    foreach ($filearray as $txt) {                     // scan array for port
     if(preg_match("/^port=(\d+)/", $txt,$matches)){   // check $text save $matches  
       $mysql_port =  $matches[1];                     // match found save port number                                   
        break;                                         // give up nothing else to do
     }
    }
  }
  else {                                              // failed to read file
    echo "Cannot read the file";
  }

if (@$_POST['submit']) {

//get old password
    $tfile = fopen(USF_MYSQL_PASSWORD, "r");
    $oldpass = fgets($tfile);

//read and set new password
    $nwpass = $_POST['qpass'];
    $urdata = "$nwpass";

// Database configuration
$dbhost = US_DB_HOST.":".$mysql_port; // server name localhost
$dbuser = 'root';                     // User name default root
$dbpass = $oldpass;                   // Set to old Password
$dbname = 'mysql';                    // database name

// Open database  
$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die('Error connecting to mysql');
mysql_select_db($dbname) or die('Cannot select database');

// Create query string 
$query = 'SET PASSWORD FOR \'root\'@\'localhost\' = PASSWORD(\'' . $nwpass . '\')';
$result = mysql_query($query);

// Create query string
$query = 'SET PASSWORD FOR \'root\'@\'127.0.0.1\' = PASSWORD(\'' . $nwpass . '\')';
$result = mysql_query($query);

// Create query string
$query = 'SET PASSWORD FOR \'root\'@\'::1\' = PASSWORD(\'' . $nwpass . '\')';
$result = mysql_query($query);

// Create query string
$query = 'SET PASSWORD FOR \'pma\'@\'localhost\' = PASSWORD(\'' . $nwpass . '\')';
$result = mysql_query($query);

$query = "FLUSH PRIVILEGES";
$result = mysql_query($query);

// Close database
mysql_close($conn);

//Crude test
if($result){
//echo "MySQL DB Updated";

//Assume database was updated write new password into file
    $wfile = fopen(USF_MYSQL_PASSWORD, 'w');
    fwrite($wfile, $urdata);
    fclose($wfile);
}
else{
//Database not written no need to change file  
echo "MySQL DB failed to update";

}

//echo"Old password = $oldpass";

?>

    <div id="main">
    <h2>&#187 <?php echo  $US['mqsetup-head']?></h2>
    <h3><?php echo  $US['mqsetup-sub-0']?></h3>
    <p>
    <?php echo  $US['mqsetup-success']?>
    <br />
    <br />
    <?php echo  $US['mqsetup-pass']?>: <?php echo $_POST['qpass'];?>
    </p>
    </div>

<?php
}

else {
    $tfile = fopen(USF_MYSQL_PASSWORD, "r");
    $fcontents = fgets($tfile);
?>
    <div id="main">
        <h2>&#187 <?php echo  $US['mqsetup-head']?></h2>
        <h3><?php echo  $US['mqsetup-sub-0']?></h3>

        <p><?php echo  $US['mqsetup-text-0']?></p>
        <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
        <table width="100%">
        <tr>
        <td width="100">
        <p><?php echo  $US['mqsetup-pass']?></p>
        </td>
        <td>
        <input type="text" name="qpass" value="<?php echo $fcontents?>" />
        </td>
        </tr>
        </table>
        <br />
        <input type="submit" name="submit" value="<?php echo  $US['mqsetup-change']?>" />
        </form>
    </div>

<?php
    fclose($tfile);
}

// Footer
include_once "includes/footer.php";
?>