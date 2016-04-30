<?php
require_once("gcm_db_connect.php");
$gcm_id_table = "gcm_id_table";

$time = date("Y-m-d H:i:s");

if(isset($_POST['regId']))
{
    $regId = $_POST['regId'];
    $query = "INSERT INTO $gcm_id_table (regId,time) VALUES ('$regId','$time')";
	mysql_query($query, $Connect_gcm) or die(mysql_error()); 
	echo "insert_success";
	$fp = fopen('regId.txt', 'a');
	fwrite($fp, "\nadd\nregId = " . $_POST['regId'] . "\ntime = " . $time . "\n\n");
	fclose($fp);
}

?>