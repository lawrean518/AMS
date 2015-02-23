<?php

	$filename = $_REQUEST['db.csv'];
	header("Content-type: text/csv");
	header("Content-Disposition: attachment; filename = '$filename'");
	
	readfile($filename);
	exit();
?>