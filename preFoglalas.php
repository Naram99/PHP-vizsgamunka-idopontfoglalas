<?php
require_once './header.php';
require_once './portfolio/portfolioprg.php';
require_once './config/db.php';

if(!isset($_SESSION['user_name'])) {
  header('location: ../login/login.php');
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous" defer></script>
  <title>Idopontfoglalas</title>
</head>
<body>
  <div class="container">
    <div class="row text-center">
      <h3>Melyik stúdióba szeretne időpontot foglalni?</h3>
    </div>
    <?php 
      $list = studioListMain($servername, $username, $password, $dbname);

      foreach($list as $nev => $leiras):
    ?>
        <div class="row">
          <a href="./idopontfoglalas.php?date=<?php print(date('Y-m-d')); ?>&std=<?php print($nev); ?>" class="btn btn-primary"><?php print($nev); ?></a>
        </div>
        <div class="row">
          <p><?php print($leiras); ?></p>
        </div>
    <?php    
      endforeach;
    ?>
  </div>
</body>
</html>