<?php
set_include_path("./src");
require_once("view/View.php");
require_once("control/Controller.php");
require_once("model/BookStorageFile.php");
require_once("model/BookBuilder.php");
require_once("model/authentification/AuthentificationManager.php");


class Router{
  public $bookStorage;
  public $auth;

  public function __construct(BookStorage $bookStorage, AuthentificationManager $auth){
    $this->bookStorage = $bookStorage;
    $this->auth = $auth;
  }

  public function main(){
    $feedback;
    if(key_exists('feedback', $_SESSION)){
      $feedback = $_SESSION['feedback'];
    }
    else{
      $feedback = '';
    }

    $vue = new View($this,$feedback,$this->auth);
    //$this->bookStorage->reinit();
    $controleur = new Controller($vue,$this->bookStorage,$this->auth);

    if(key_exists('id',$_GET)){
      $id = $_GET['id'];
      $controleur->showInformation($id);
    }
    else if(key_exists('liste',$_GET)){
      $controleur->showList();
    }
    else if(key_exists('connexion',$_GET)){
      $controleur->authentification($_POST);
    }
    else if(key_exists('about',$_GET)){
      $vue->makeAboutPage();
    }
    else if(key_exists('source',$_GET)){
      $vue->makeSourcePage();
    }
    else if(key_exists('action',$_GET)){
      if($this->auth->isUserConnected()){
        $array=array(BookBuilder::TITLE_REF => "", BookBuilder::AUTHOR_REF=>"",BookBuilder::VOLUME_REF=>"",BookBuilder::SYNOPSIS_REF=>"");
        $book=new BookBuilder($array);
        if($_GET['action']==="nouveau"){
          $vue->makeBookCreationPage($book);
        }
        else if($_GET['action']==="sauverNouveau"){
          $controleur->saveNewBook($_POST);
        }
      }
      else{
        $vue->makeUnknowBookPage();
      }
    }
    else if(key_exists('askSuppression',$_GET)){
      $idSuppression=$_GET['askSuppression'];
      $controleur->askBookDeletion($idSuppression);
    }
    else if(key_exists('suppression',$_GET)){
      $idSupp=$_GET['suppression'];
      if(key_exists('confirme', $_POST)){
        $controleur->deleteBook($idSupp);
      }
    }
    else if(key_exists('modification',$_GET)){
      $idModif=$_GET['modification'];
        $vue->makeChangePage($idModif);
    }
    else if(key_exists('sauverModification',$_GET)){
      $idModifSauve=$_GET['sauverModification'];
        $controleur->saveChangeBook($_POST,$idModifSauve);
    }
    else if(key_exists('inscription',$_GET)){
        $vue->makeLoginRegisterPage("");
    }
    else if(key_exists('registerSave',$_GET)){
        $controleur->registration($_POST);
    }
    else{
      $vue->makeHomePage();
    }
    $vue->render();
  }

  public function getBookURL($id){
    return $_SERVER['PHP_SELF']."?id=$id";
  }

  public function getBookCreationURL(){
    return $_SERVER['PHP_SELF']."?action=nouveau";
  }

  public function getBookSaveURL(){
    return $_SERVER['PHP_SELF']."?action=sauverNouveau";
  }

  public function getModifURL($id){
    return $_SERVER['PHP_SELF']."?modification=$id";
  }

  public function getModifSaveURL($id){
    return $_SERVER['PHP_SELF']."?sauverModification=$id";
  }

  public function getBookAskDeletionURL($id){
    return $_SERVER['PHP_SELF']."?askSuppression=$id";
  }

  public function getBookDeletionURL($id){
    return $_SERVER['PHP_SELF']."?suppression=$id";
  }

  public function getAccountURL(){
    return $_SERVER['PHP_SELF']."?connexion";
  }

  public function getAccountRegisterURL(){
    return $_SERVER['PHP_SELF']."?inscription";
  }

  public function getAccountRegisterSaveURL(){
    return $_SERVER['PHP_SELF']."?registerSave";
  }

  public function getHomeURL(){
    return $_SERVER['PHP_SELF'];
  }

  public function getAboutURL(){
    return $_SERVER['PHP_SELF']."?about";
  }

  public function getSourceURL(){
    return $_SERVER['PHP_SELF']."?source";
  }

  public function POSTredirect($url, $feedback) {
		$_SESSION['feedback'] = $feedback;
		header("Location: ".$url, true, 303);
		die;
	}
}

?>
