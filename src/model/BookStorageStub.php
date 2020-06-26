<?php
set_include_path("./src/model");
require_once("BookStorage.php");
require_once("Book.php");

class BookStorageStub implements BookStorage{
  public $booksTab;
  public $phobos;
  public $alive;
  public $mlo;

  public function __construct(){
    ob_start();
    include "view/fragment/synopsis_phobos_1.txt";
    $this->phobos = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_alive.txt";
    $this->alive = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_mlo_1.txt";
    $this->mlo = ob_get_clean();

    $this->booksTab=array(
          	'phobos' => new Book('Phobos', 'Victor Dixen','1','phobos_t1.jpg', $this->phobos),
          	'alive' => new Book('Alive', 'Scott Sigler', '1','alive_t1.jpg', $this->alive),
          	'mlo' => new Book('Marquer les ombres', 'Veronica Ross','1','mlo.jpg', $this->mlo),
          );
  }

  public function read($id){
    if(key_exists($id, $this->booksTab)){
      return $this->booksTab[$id];
    }
    return null;
  }

  public function readAll(){
    return $this->booksTab;
  }

  public function create(Book $book){
    return $this->booksTab=$book;
  }
}
?>
