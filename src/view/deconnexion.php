<?php
	session_name("Connexion");
	session_start();
	include_once('comptes.php');
	include_once('AuthentificationManager.php');
	$serv = new AuthentificationManager($comptes);
	$serv->disconnectUser();
	header('location:'.$_SERVER['PHP_SELF']);
?>
