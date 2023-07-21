<?php
/**
 * redirection vers la page de la formation choisie
 */
include 'functions.php';

	$dbh = db_connection();
	$sth = $dbh->prepare('SELECT id FROM parcoursup WHERE lib_comp_voe_ins = "' .$_POST['formation']. '"');
	
	$sth->execute();
	$id = $sth->fetchAll();
	
	header('Location: ../../info.php?id='.$id[0][0]);
?>
