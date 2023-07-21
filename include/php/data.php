<?php
require_once 'functions.php';
$query = $_POST['query'];
$dbh = db_connection();
$sth = $dbh->prepare($query);
$sth->execute();
$results = $sth->fetchAll(PDO::FETCH_OBJ);
echo json_encode($results);
?>