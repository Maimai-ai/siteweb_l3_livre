<?php
set_include_path("./src");
require_once("model/Book.php");

class BookBuilder{
  private $data;
  private $error;
  const TITLE_REF='titre';
  const AUTHOR_REF='auteur';
  const VOLUME_REF='tome';
  const SYNOPSIS_REF='synopsis';

  public function __construct($data){
    $this->data=$data;
    $this->error=array();
  }

  public function getData($ref){
    return $this->data[$ref];
  }

  public function setData($ref,$str){
    $this->data[$ref]=$str;
  }

  public function getError(){
    $chaine="";
    foreach ($this->error as $key => $value) {
      $chaine.=$value."<br>";
    }
    return $chaine;
  }

  public function createBook(){
    $book = new Book($this->data[self::TITLE_REF], $this->data[self::AUTHOR_REF], $this->data[self::VOLUME_REF], "unknow.jpg", $this->data[self::SYNOPSIS_REF]);
    return $book;
  }

  public function isValid(){
    $this->error=array();
    if(!key_exists(self::TITLE_REF,$this->data) || $this->data[self::TITLE_REF]===""){
      $this->error[self::TITLE_REF]="Entrez le titre du livre";
    }
    else if (mb_strlen($this->data[self::TITLE_REF], 'UTF-8') >= 50){
			$this->error[self::TITLE_REF] = "Le titre doit faire moins de 50 caractères";
    }
    if (!key_exists(self::AUTHOR_REF,$this->data) || $this->data[self::AUTHOR_REF]=== ""){
			$this->error[self::AUTHOR_REF] ="Entrez le nom de l'auteur";
    }
    else if (mb_strlen($this->data[self::AUTHOR_REF], 'UTF-8') >= 50){
			$this->error[self::AUTHOR_REF] = "Le nom de l'auteur doit faire moins de 50 caractères";
    }
    // if (!key_exists(self::VOLUME_REF,$this->data) || $this->data[self::VOLUME_REF]=== ""){
		// 	$this->error[self::VOLUME_REF] ="Entrez le tome du livre";
    // }
    // else if (mb_strlen($this->data[self::VOLUME_REF], 'UTF-8') >= 6){
		// 	$this->error[self::VOLUME_REF] = "Mettez seulement le numéro de tome";
    // }
    if(!key_exists(self::SYNOPSIS_REF,$this->data) || $this->data[self::SYNOPSIS_REF]===""){
      $this->error[self::SYNOPSIS_REF]="Entrez le synopsis du livre";
    }
    else if (mb_strlen($this->data[self::SYNOPSIS_REF], 'UTF-8') < 10 ){
			$this->error[self::SYNOPSIS_REF] = "Le synopsis doit faire au moins 10 caractères";
    }
    if (count($this->error) == 0){
      return true;
    }
    else{
      return false;
    }
  }

  public function isChange($ref){
    if(key_exists($ref,$this->data)){
      if($this->data[$ref]===""){
        return false;
      }
    }
    return true;
  }
}

?>
