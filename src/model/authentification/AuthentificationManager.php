<?php

class AuthentificationManager{
  private $account;

  public function __construct(array $account){
    $this->account=$account;
  }

  function connectUser($login, $password){
    foreach($this->account as $key){
      if($key->getLogin() === $login && password_verify($password,$key->getMdp())){
        $_SESSION['user'] = $key->getNom();
        $_SESSION['login']= $key->getLogin();
        $_SESSION['statut'] =$key->getStatut();
        return true;
      }
    }
    return false;
  }

  public function isUserConnected(){
    if(key_exists('login',$_SESSION)){
			return true;
		}
		return false;
	}

  public function userExist($login){
    foreach($this->account as $key){
      if($key->getLogin() === $login){
        return true;
      }
    }
    return false;
  }

  public function register($nom,$login,$password){
    $newAccount=new Account($nom,$login,$password,'user');
    $this->account=$newAccount;
  }

  public function isAdminConnected(){
    if(key_exists('statut',$_SESSION)){
      if($_SESSION['statut']==='admin'){
        return true;
      }
    }
    else{
      return false;
    }
  }

  public function getUserName(){
    if(key_exists('user',$_SESSION)){
      return $_SESSION['user'];
    }
    else{
      throw new Exception("Not Connected");
    }
  }

  public function disconnectUser(){
    return session_unset();
  }

  public function getError(){
    $chaine="<span class='error'>Erreur votre mot de passe ou votre login est faux !</span>";
    return $chaine;
  }
}
?>
