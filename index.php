<?php
require_once './header.php';
require_once './config/db.php';
require_once './portfolio/portfolioprg.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Alpha Studio</title>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous" defer></script>
  </head>
  <body>
    <section style="background: url(./assets/images/bg-main.jpg) center/cover no-repeat;" class="h-100 w-100 d-flex flex-column">
      <div class="text-center w-100">
        <img src="./assets/images/alpha.png" alt="" class="h-100">
      </div>
      <div class="container d-flex justify-content-between align-items-center h-100">
        <a href="#studiok" class="btn btn-primary">Stúdiók</a>
        <a href="./portfolio/portfolio.php" class="btn btn-primary">Portfólió</a>
        <a href="./idopontfoglalas/idopontfoglalas.php" class="btn btn-primary">Időpontfoglalás</a>
      </div>
    </section>
    <section id="studiok">
      <div class="container">
        <div class="row">
          <h3 class="text-center">Stúdióink:</h3>
        </div>

        <!-- STÚDIÓK listázása -->
        <?php 
          $list = studioListMain($servername, $username, $password, $dbname);
          foreach($list as $nev => $leiras) {
            print('<div class="row"><h5>'.$nev.'</h5></div>');
            print('<div class="row"><p>'.$leiras.'</p></div>');
          }
        ?>
        <a href="./idopontfoglalas/idopontfoglalas.php" class="btn btn-primary">Időpontfoglalás</a>
      </div>
    </section>
  </body>
</html>
