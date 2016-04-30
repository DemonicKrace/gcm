<?php
require_once("gcm_db_connect.php");
$gcm_id_table = "gcm_id_table";

$time = date("Y-m-d H:i:s");

if(isset($_POST['regId']))
{
    $regId = $_POST['regId'];
    $query = "DELETE FROM $gcm_id_table WHERE regId = '$regId'";
	mysql_query($query, $Connect_gcm) or die(mysql_error()); 
	echo "delete_success";
	$fp = fopen('regId.txt', 'a');
	fwrite($fp, "\ndelete\nregId = " . $_POST['regId'] . "\ntime = " . $time . "\n\n");
	fclose($fp);
}

?>