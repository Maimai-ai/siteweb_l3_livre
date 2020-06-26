<?php

set_include_path("./src");
require_once("Router.php");
require_once("model/Book.php");
require_once("model/authentification/AccountStorage.php");
require_once("model/authentification/AuthentificationManager.php");
include_once("model/authentification/comptes.php");

class View{
  private $title;
  private $content;
  private $connexion;
  private $menu;
  private $router;
  private $frg;
  private $frgAbout;
  private $frgSource;
  private $feedback;
  private $vueFeedBack;
  private $auth;

  function __construct(Router $router, $feedback, AuthentificationManager $auth) {
    $this->title="";
    $this->content="";
    $this->content="";
    $this->router=$router;
    $this->feedback = $feedback;
    $this->vueFeedBack = "";
    $this->auth=$auth;

    //Permet de récuperer un fragment de l'accueil
    ob_start();
    include "view/fragment/frg_accueil.txt";
    $this->frg = ob_get_clean();

    ob_start();
    include "view/fragment/frg_source.txt";
    $this->frgSource = ob_get_clean();

    ob_start();
    include "view/fragment/frg_apropos.txt";
    $this->frgAbout = ob_get_clean();

    $this->menu="<a href='".$this->router->getHomeURL()."'> ACCUEIL </a>";
    $this->menu.="<a href='".$this->router->getHomeURL()."?liste'> LISTE DES LIVRES </a>";
    if($this->auth->isUserConnected()){
      $this->menu.="<a href='".$this->router->getBookCreationURL()."'> AJOUT DE LIVRE </a>";
    }
    $this->menu.="<a href='".$this->router->getAboutURL()."'> À PROPOS </a>";
    if($this->auth->isAdminConnected()){
      $this->menu.="<a href='".$this->router->getSourceURL()."'> SOURCE </a>";
    }
    $this->connexion="<a href='".$this->router->getAccountURL()."'>  CONNEXION/DECONNEXION </a> <br> <a href='".$this->router->getAccountRegisterURL()."'>INSCRIPTION</a>";
  }

  function render(){
    if($this->feedback !==''){
      $this->vueFeedBack = "<div class='feedback'>".$this->feedback."</div>";
    }
    include("squelette.php");
  }

  public function makeHomePage() {
		$this->title = "Accueil";
		$this->content = $this->frg;
	}

  public function makeAboutPage() {
    $this->title = "À propos";
    $this->content = $this->frgAbout;
  }

  public function makeSourcePage() {
    $this->title = "Sources";
    $this->content = $this->frgSource;
  }

  function makeBookPage(Book $book){
    $this->title=$book->getTitle();
    $this->content="<img src='src/view/img/".$book->getImg()."' alt='test' />";
    $this->content.="<div id=description>";
    $this->content.= "<br><span class='desc'>Titre : </span>".$book->getTitle();
    if($book->getVolume()!=="" && $book->getVolume()!=='0'){
        $this->content.= "<br><span class='desc'>Tome : </span>".$book->getVolume();
    }
    $this->content.="<br><span class='desc'>Auteur : </span>".$book->getAuthor();
    $this->content.="<br><span class='desc'>Synopsis : </span>". $book->getSynopsis();
    $this->content.="</div>";
    if(key_exists('id',$_GET)){
      $id = $_GET['id'];
      $this->content.="<form action=".$this->router->getModifURL($id)." method='POST'>";
      $this->content.="<input type='submit' value='Modifier'/></form>";
      $this->content.="<form action=".$this->router->getBookAskDeletionURL($id)." method='POST'>";
      $this->content.="<input type='submit' value='Supprimer'/></form>";
    }
  }

  function makeUnknowBookPage(){
    $this->title="Erreur";
    $this->content="La page demandée est inconnu";
  }

  function makeListPage($tableau){
    $this->title="Liste de Livres";
    $this->content="<ul class='livre' >";
    $href="";
    $hrefEnd="";
    foreach($tableau as $tab => $key){
      $this->content .="<li> <figure>";
      if($this->auth->isUserConnected()){
        $href.="<a href='".$this->router->getBookURL($tab)."'>";
        $hrefEnd.= "</a>";
      }
      $this->content.=$href."<img src='src/view/img/".$key->getImg()."' alt='".$key->getTitle()."' /> <figcaption>".$key->getTitle()." ";
      if($key->getVolume()!=="" || $key->getVolume()!=='0'){
        $this->content.="- ".$key->getVolume();
      }
      $this->content.="</figcaption></figure>".$hrefEnd."</li>";
    }
    $this->content .="</ul>";
  }

  public function makeDebugPage($variable) {
  	$this->title = 'Debug';
  	$this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
  }

  public function makeBookCreationPage(BookBuilder $bookBuilder){
    $this->content="<form action=".$this->router->getBookSaveURL()." method='POST'>";
    $this->content.="<p><label>Titre : <input type='text' name='".BookBuilder::TITLE_REF."' value='".self::htmlesc($bookBuilder->getData(BookBuilder::TITLE_REF))."' /> </label> </p>";
    $this->content.="<p><label>Auteur</label> : <input type='text' name='".BookBuilder::AUTHOR_REF."' value='".self::htmlesc($bookBuilder->getData(BookBuilder::AUTHOR_REF))."' /> </p>";
    $this->content.="<p><label>Tome</label> : <input type='text' name='".BookBuilder::VOLUME_REF."' value='".self::htmlesc($bookBuilder->getData(BookBuilder::VOLUME_REF))."' /></p>";
    $this->content.="<p><label>Synopsis</label> : <textarea name='".BookBuilder::SYNOPSIS_REF."' placeholder='Dit moi en plus...'>".self::htmlesc($bookBuilder->getData(BookBuilder::SYNOPSIS_REF))."</textarea> </p>";
    $this->content.="<input type='submit' value='Envoyer'/></form>";
    if ($bookBuilder->getError() !== ""){
			$this->content.= ' <span class="error">'.$bookBuilder->getError().'</span>';
    }
  }

  public function makeBookDelectionPage($id){
    $this->content.="<form action=".$this->router->getBookDeletionURL($id)." method='POST'>";
    $this->content.="<input type='submit' name='confirme' value='Confirmez !'> <input type='submit' value='Annuler'></form>";
  }

  public function makeLoginFormPage($error){
    if(key_exists('user',$_SESSION)){
      $this->title="Deconnexion";
      $this->content="<p> Vous vous êtes bien connecté : ".$this->auth->getUserName()."</p> <br>";
      $this->content.="<form action=".$this->router->getAccountURL()." method='POST'>";
      $this->content.="<input type='submit' name='deco' value='Deconnexion'/></form>";
    }
    else{
      $this->title="Connexion";
      $this->content="<form action=".$this->router->getAccountURL()." method='POST'>";
      $this->content.="<p><label>Login</label> : <input type='text' name='login' /> ";
      $this->content.="<label>Mot de passe</label> : <input type='password' name='mdp' /> ";
      $this->content.="</p><input type='submit' value='Connexion'/></form> <br>";
    }
    $this->content.= ' <span class="error">'.$error.'</span>';
  }

  public function makeLoginRegisterPage($info){
      $this->title="Inscription";
      $this->content= '<span class="error">'.$info.'</span>';
      $this->content.="<form action=".$this->router->getAccountRegisterSaveURL()." method='POST'>";
      $this->content.="<p><label>Prenom</label> : <input type='text' name='name' /> ";
      $this->content.="<label>Login</label> : <input type='text' name='login' /> ";
      $this->content.="<label>Mot de passe</label> : <input type='password' name='mdp' /> ";
      $this->content.="</p><input type='submit' value='Inscription'/></form>";
  }

  public function makeChangePage($id){
    $this->content.="<form action=".$this->router->getModifSaveURL($id)." method='POST'>";
    $this->content.="<p><label>Titre : <input type='text' name='".BookBuilder::TITLE_REF."' /> </label> </p>";
    $this->content.="<p><label>Auteur</label> : <input type='text' name='".BookBuilder::AUTHOR_REF."' /> </p>";
    $this->content.="<p><label>Tome</label> : <input type='text' name='".BookBuilder::VOLUME_REF."'  /></p>";
    $this->content.="<p><label>Synopsis</label> : <textarea name='".BookBuilder::SYNOPSIS_REF."' > </textarea> </p>";
    $this->content.="<input type='submit' value='Envoyer'/></form>";
  }

    /* Une fonction pour échapper les caractères spéciaux de HTML,
  * car celle de PHP nécessite trop d'options. */
  public static function htmlesc($str) {
    return htmlspecialchars($str,
      /* on échappe guillemets _et_ apostrophes : */
      ENT_QUOTES
      /* les séquences UTF-8 invalides sont
      * remplacées par le caractère �
      * au lieu de renvoyer la chaîne vide…) */
      | ENT_SUBSTITUTE
      /* on utilise les entités HTML5 (en particulier &apos;) */
      | ENT_HTML5,
      'UTF-8');
  }
}

?>
