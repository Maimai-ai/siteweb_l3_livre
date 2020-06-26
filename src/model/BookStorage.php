<?php
interface BookStorage{
  public function read($id);
  public function readAll();
  public function create(Book $book);
  public function delete($id);
}
?>
