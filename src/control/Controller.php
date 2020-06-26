<?php
set_include_path("./src");
require_once("view/View.php");
require_once("model/Book.php");
require_once("model/BookStorage.php");
require_once("model/BookBuilder.php");
require_once("model/authentification/AuthentificationManager.php");
require_once("model/authentification/AccountStorageStub.php");

class Controller{
  private $view;
  private $bookStorage;
  private $auth;

  public function __construct($view, BookStorage $bookStorage, AuthentificationManager $auth){
    $this->view=$view;
    $this->bookStorage=$bookStorage;
    $this->auth=$auth;
  }

  public function showInformation($id){
    $read=$this->bookStorage->read($id);
    if($read==null){
      $this->view->makeUnknowBookPage();
    }
    else{
      if($this->auth->isUserConnected()){
        $this->view->makeBookPage($read);
      }
      else{
        $this->view->makeUnknowBookPage();
      }
    }
  }

  public function showList(){
    $tab=$this->bookStorage->readAll();
    $this->view->makeListPage($tab);
  }

  public function saveNewBook(array $data){
    $bookbuilder=new BookBuilder($data);
    if($bookbuilder->isValid()==false){
      $this->view->makeBookCreationPage($bookbuilder);
    }
    else{
      $newBook=new Book($bookbuilder->getData(BookBuilder::TITLE_REF),$bookbuilder->getData(BookBuilder::AUTHOR_REF),$bookbuilder->getData(BookBuilder::VOLUME_REF),'unknow.jpg',$bookbuilder->getData(BookBuilder::SYNOPSIS_REF));
      $this->bookStorage->create($newBook);
      $this->view->makeBookPage($newBook);
    }
  }

  public function saveChangeBook(array $data,$id){
    $bookbuilder=new BookBuilder($data);
    $book = $this->bookStorage->read($id);
    //var_dump($data);
    if(key_exists(BookBuilder::TITLE_REF,$data)){
      if($bookbuilder->isChange(BookBuilder::TITLE_REF)){
        $book->setTitle($bookbuilder->getData(BookBuilder::TITLE_REF));
      }
    }
    if(key_exists(BookBuilder::AUTHOR_REF,$data)){
      if($bookbuilder->isChange(BookBuilder::AUTHOR_REF)){
        $book->setAuthor($bookbuilder->getData(BookBuilder::AUTHOR_REF));
      }
    }
    if(key_exists(BookBuilder::VOLUME_REF,$data)){
      if($bookbuilder->isChange(BookBuilder::VOLUME_REF)){
        $book->setVolume($bookbuilder->getData(BookBuilder::VOLUME_REF));
      }
    }
    if(key_exists(BookBuilder::SYNOPSIS_REF,$data)){
      if($bookbuilder->isChange(BookBuilder::SYNOPSIS_REF)){
        $book->setSynopsis($bookbuilder->getData(BookBuilder::SYNOPSIS_REF));
      }
    }
    $this->view->makeBookPage($book);
  }


  public function askBookDeletion($id){
    if($this->bookStorage->existe($id) && $this->auth->isUserConnected()){
      $this->view->makeBookDelectionPage($id);
    }
    else{
      $this->view->makeUnknowBookPage();
    }
  }

  public function deleteBook($id){
    $this->bookStorage->delete($id);
    $tab=$this->bookStorage->readAll();
    $this->view->makeListPage($tab);
  }

  public function authentification(array $data){
    $error="";
    if(key_exists('login',$data) && key_exists('mdp',$data) && !key_exists('user', $_SESSION)){
      $this->auth->connectUser($data['login'], $data['mdp']);
      if(!$this->auth->connectUser($data['login'], $data['mdp'])){
        $error="Erreur votre mot de passe ou votre login est faux !";
      }
    }
    if(key_exists('deco', $data)){
      $this->auth->disconnectUser();
      header('location: '.$_SERVER['PHP_SELF']);
    }
    $this->view->makeLoginFormPage($error);
  }

  public function registration(array $data){
    $info="";
    if(key_exists('login',$data)){
      if($this->auth->userExist($data['login'])==true){
        $info="Ce nom d'utilisateur existe déjà !";
        var_dump("Erreur");
        $this->view->makeLoginRegisterPage($info);
      }
    }
    else if(key_exists('login',$data) && key_exists('mdp',$data) && key_exists('name',$data)){
      $hash = password_hash($data['mdp'], PASSWORD_BCRYPT);
      $this->auth->register($data['name'],$data['login'],$hash);
      $this->auth->connectUser($data['login'],$data['mdp']);
      var_dump("COnnection");
      $this->view->makeLoginFormPage("Bienvenue sur le site : ". $data['name']);
    }
    else{
      var_dump("Erreur");
    }
  }

}
?>
