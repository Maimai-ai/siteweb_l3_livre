<?php
set_include_path("./src");
require_once("model/authentification/Account.php");

interface AccountStorage{
  public function checkAuth($login, $password);
}

?>
