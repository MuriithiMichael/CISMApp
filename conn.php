<?php

$mysqli= new mysqli("localhost","root","","a2361141_ebank");
if($mysqli->connect_errno){
	echo "Failed to connect to MYSQL: (".$mysqli->connect_errno." )".$mysqli->connect_error;
}

?>