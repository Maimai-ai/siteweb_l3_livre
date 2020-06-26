
<!DOCTYPE html>
<html>
  <head>
    <link rel = "stylesheet" href="skin/style.css"/>
    <title>Mon coin de lecture</title>
  </head>
  <body>
    <div class="article">
    <header>
      <h1> LE COIN DES LECTEURS </h1>
      <nav id="connexion">
        <?php echo $this->connexion; ?>
      </nav>
      <nav id="menu">
        <?php echo $this->menu; ?>
      </nav>
    </header>
    <!--<img src='src/view/img/mlo.jpg' alt='test' />-->

      <h2><?php echo $this->title;?></h2>
      <p><?php echo $this->vueFeedBack;?></p>
      <p><?php echo $this->content;?></p>
    </div>
    <hr/>
    <footer>
      <p>Création de site</p>
      <p>Licence informatique</p>
      <p>Troisième année</p>
      <p>Année de création : 2019</p>
    </footer>
  </body>
</html>
