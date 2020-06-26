<?php
set_include_path("./src");
require_once("model/BookStorage.php");
require_once("lib/ObjectFileDB.php");

class BookStorageFile implements BookStorage{
  private $db;

  public function __construct($file){
    $this->db=new ObjectFileDB($file);
    ob_start();
    include "view/fragment/synopsis_phobos_1.txt";
    $this->phobos = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_alive.txt";
    $this->alive = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_alight.txt";
    $this->alight = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_mlo_1.txt";
    $this->mlo = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_mlo_2.txt";
    $this->mlo_2 = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_nil_1.txt";
    $this->nil_1 = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_cogito.txt";
    $this->cogito = ob_get_clean();
    ob_start();
    include "view/fragment/synopsis_warcross_1.txt";
    $this->warcross = ob_get_clean();
    
  }

  public function reinit(){
    $this->db->deleteAll();
    $this->db->insert(new Book('Phobos', 'Victor Dixen','1','phobos_t1.jpg', $this->phobos));
    $this->db->insert(new Book('Alive', 'Scott Sigler', '1','alive_t1.jpg', $this->alive));
    $this->db->insert(new Book('Alight', 'Scott Sigler', '2','alight_t2.jpg', $this->alight));
    $this->db->insert(new Book('Marquer les ombres', 'Veronica Ross','1','mlo.jpg', $this->mlo));
    $this->db->insert(new Book('Marquer les ombres', 'Veronica Ross','2','mlo_t2.jpg', $this->mlo_2));
    $this->db->insert(new Book('Nil', 'Lynn Matson','1','nil_t1.jpg', $this->nil_1));
    $this->db->insert(new Book('Cogito', 'Victor Dixen','0','cogito.jpg', $this->cogito));
    $this->db->insert(new Book('Warcross', 'Marie Lu','1','warcross_t1.jpg', $this->warcross));

  }

  public function read($id){
    if($this->db->exists($id)){
      return $this->db->fetch($id);
    }
    return null;
  }

  public function readAll(){
    return $this->db->fetchAll();
  }

  public function create(Book $book){
    return $this->db->insert($book);
  }

  public function delete($id){
    return $this->db->delete($id);
  }

  public function existe($id){
    return $this->db->exists($id);
  }
}
?>
