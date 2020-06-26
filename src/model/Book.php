<?php
class Book{
  private $title;
  private $author;
  private $volume;
  private $img;
  private $synopsis;

  public function __construct($title,$author,$volume,$img,$synopsis){
    $this->title=$title;
    $this->author=$author;
    $this->volume=$volume;
    $this->img= $img;
    $this->synopsis=$synopsis;
  }

  public function getTitle(){
    return $this->title;
  }

  public function getAuthor(){
    return $this->author;
  }

  public function getVolume(){
    return $this->volume;
  }

  public function getImg(){
    return $this->img;
  }

  public function getSynopsis(){
    return $this->synopsis;
  }

  public function setTitle($titre){
    $this->title=$titre;
  }

  public function setAuthor($author){
    $this->author=$author;
  }

  public function setVolume($volume){
    $this->volume=$volume;
  }

  public function setImg($img){
    $this->img=$img;
  }

  public function setSynopsis($synop){
    $this->synopsis=$synop;
  }
}
?>
