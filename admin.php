<?php 
require_once './header.php';
require_once './admin/adminPortfolioFunctions.php';
require_once './config/db.php';

if($_SESSION['user_name'] !== 'admin') {
  header('location: ./index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous" defer></script>
  <title>Admin</title>
</head>
<body>
  <!-- IDŐPONTOK -->
  <section>

  </section>
  <!-- STÚDIÓK KEZELÉSE -->
  <section>
    <div class="container">
      <div class="row">
        <h3 class="text-center">Új stúdió hozzáadása</h3>
      </div>
      <div class="row">
        <form action="" method="POST" class="form">
          <label for="nev" class="label">Stúdió neve: </label>
          <input type="text" id="nev" name="nev" class="form-control" required>
          <label for="leiras" class="label">Stúdió leírása: </label>
          <input type="text" id="leiras" name="leiras" class="form-control" required>
          <label for="fotosnev" class="label">Fotós neve: </label>
          <input type="text" id="fotosnev" name="fotosnev" class="form-control" required>
          <button type="submit" class="btn btn-primary">Hozzáadás</button>
        </form>
      </div>
      <?php 
        if(isset($_POST['nev']) && isset($_POST['leiras']) && isset($_POST['fotosnev'])) {
          $studionev = $_POST['nev'];
          $studioleiras = $_POST['leiras'];
          $fotosneve = $_POST['fotosnev'];

          studioInsert($servername, $username, $password, $dbname, $studionev, $studioleiras, $fotosneve);
        }
      ?>
    </div>
  </section>
    
  <section>
    <div class="container">
      <div class="row">
        <h3 class="text-center">Stúdió törlése</h3>
      </div>
      <?php 
        $list = studioList($servername, $username, $password, $dbname);
        if($list) {
          //print('<div class="row">'.var_dump($list).'</div>');

          foreach($list as $id => $nev) {
            print('<div class="row">'.$id.' '.$nev.'</div>');
          }
        }
      ?>
      <form action="" method="POST" class="form">
        <label for="id" class="label">Írd be a törölni kívánt stúdió számát: </label>
        <input type="text" id="id" name="id" class="form-control" required>
        <button type="submit" class="btn btn-primary">Törlés</button>
      </form>
      <?php 
        if(isset($_POST['id'])) {
          $studioId = $_POST['id'];

          studioDelete($servername, $username, $password, $dbname, $studioId);
        }
      ?>
    </div>
  </section>
  <!-- KÉPFELTÖLTÉS -->
  <section>
    <div class="container">
      <div class="row">
        <h3 class="text-center">Portfólió kép feltöltés</h3>
      </div>
      <div class="row">
        <form action="" method="POST" class="form" enctype="multipart/form-data">
          <label for="picture" class="label">Fájl: </label>
          <input type="file" id="picture" name="picture" class="form-control" required>
          <?php 
          $list = studioList($servername, $username, $password, $dbname);
          if($list) {
            foreach($list as $id => $nev) {
        ?>
            <label for="radioU<?php print($id); ?>"><?php print($nev); ?>: </label>
            <input type="radio" id="radioU<?php print($id); ?>" name="studio" value=<?php print($id); ?> required>
        <?php
            }
          }
        ?>
          <button type="submit" class="btn btn-primary">Feltöltés</button>
        </form>
      </div>
      <?php 
    if(isset($_FILES['picture']) && isset($_POST['studio']) && $_FILES['picture']['error'] === 0) {
      $studioId = $_POST['studio'];
      $pictureName = $_FILES['picture']['name'];
      $kiterjesztes = pathinfo($pictureName, PATHINFO_EXTENSION);
      $picname = uniqid('picture_').'.'.$kiterjesztes;
      
      if(copy($_FILES['picture']['tmp_name'], './assets/images/'.$picname)) {

        portfolioUpload($servername, $username, $password, $dbname, $studioId, $picname);
      } else {
        print('<div class="row">A fájlt nem sikerült lementeni</div>');
      }
    } 
    ?>
    </div>
    
  </section>
  <!-- IDŐPONTOK -->
  <!-- HOZZÁADÁS -->
  <section>
    <div class="container">
      <div class="row">
        <h3 class="text-center">Időpont hozzáadása</h3>
      </div>
      <form action="" method="POST" class="form">
        <label for="newDate"></label>
        <input type="datetime-local" id="newDate" name="newDate" class="form-control" min="<?php print(date('Y-m-d')); ?>" required>
        <?php 
          $list = studioList($servername, $username, $password, $dbname);
          if($list) {
            foreach($list as $id => $nev) {
        ?>
            <label for="radio<?php print($id); ?>"><?php print($nev); ?>: </label>
            <input type="radio" id="radio<?php print($id); ?>" name="studio" value="<?php print($id); ?>" required>
        <?php
            }
          }
        ?>
        <button type="submit" class="btn btn-primary">Hozzáadás</button>
      </form>
      <?php 
        if(isset($_POST['newDate']) && isset($_POST['studio'])) {
          $ujidopont = $_POST['newDate'];
          $studio = $_POST['studio'];

          ujIdopont($servername, $username, $password, $dbname, $ujidopont, $studio);
        }
      ?>
    </div>
  </section>
  <!-- LEFOGLALT IDŐPONTOK LISTÁZÁSA -->
  <section>
    <div class="container">
      <h3 class="row">Foglalt időpontok:</h3>
      <?php 
        $foglaltIdo = idopontLista($servername, $username, $password, $dbname);
        foreach($foglaltIdo as $foglalas):
      ?>
        <div class="row">
          <p><?php print($foglalas.' perc'); ?></p>
        </div>
      <?php
        endforeach;
      ?>
    </div>
  </section>
</body>
</html>