<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");
require_once("model/authentification/AuthentificationManager.php");

session_name("monSiteID");
session_start();
/*
 * Cette page est simplement le point d'arrivée de l'internaute
 * sur notre site. On se contente de créer un routeur
 * et de lancer son main.
 */
include_once("src/model/authentification/comptes.php");
$serv = new AuthentificationManager($comptes);

// $hash = password_hash('abc1234', PASSWORD_BCRYPT);
// var_dump($hash);
// var_dump(password_verify('abc1234','$2y$10$x3UzGMHciaDPWAL0hLj.peiOTiHKgp6YJHIyHeSBCQjQF7EC60cge'));

$router = new Router(new BookStorageFile('/users/21605680/private/mysql_config.php'),$serv);
$router->main();
?>
