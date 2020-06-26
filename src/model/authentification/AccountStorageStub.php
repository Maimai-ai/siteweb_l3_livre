<?php
set_include_path("./src");
require_once("model/authentification/AccountStorage.php");

class AccountStorageStub implements AccountStorage{
  private $tabAccount;

  public function __construct(){
    $this->tabAccount=array(
      'toto' => new Account('toto','toto','toto123','admin'),
      'tutu' => new Account('tutu','tutu','azerty','user'),
      'tata' => new Account('tata','tata','123456ab','user'),
    );
  }

  public function checkAuth($login,$password){
    if(key_exists($login, $this->tabAccount)){
      if($this->tabAccount[$login]->getMdp()==$password){
        return $this->tabAccount[$login];
      }
    }
    return null;
  }

}

?>
