<?php
$hostname_Connect_gcm = ""; //database address
$database_Connect_gcm = "";	//database name
$username_Connect_gcm = "";	//user
$password_Connect_gcm = "";	//pw
$Connect_gcm = mysql_pconnect($hostname_Connect_gcm, $username_Connect_gcm, $password_Connect_gcm) or trigger_error(mysql_error(),E_USER_ERROR); 
mysql_query("SET NAMES 'UTF8'",$Connect_gcm); //設為utf8
mysql_query("SET CHARACTER_SET_CLIENT= 'UTF8'"); 
mysql_query("SET CHARACTER_SET_RESULTS='UTF8'");
mysql_select_db($database_Connect_gcm); //選擇資料庫
?>