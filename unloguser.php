<?php
	session_name('EFSESSION');
	session_start();
	session_destroy();
	session_unset();
	header('refresh:0;url=index.php');
?>
