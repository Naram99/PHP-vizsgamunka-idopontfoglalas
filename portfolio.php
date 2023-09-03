<?php 
require_once './header.php';
require_once './portfolio/portfolioprg.php';
require_once './config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous" defer></script>
  <title>Portfolio</title>
</head>
<body>
  <div class="container">
  <?php
    $portfolioFotok = portfolioF($servername, $username, $password, $dbname);
    foreach($portfolioFotok as $studio => $fotok):
  ?>
    <div class="row">
      <div class="col-12 text-center"><h2><?php print($studio); ?></h2></div>
    </div>
    <div class="row">
    <?php foreach($fotok as $key => $foto): ?>
      <div class="col-4">
        <img src="./assets/images/<?php print($foto) ?>" alt="portfólió kép" width="100%">
      </div>
    <?php 
        endforeach;
    ?>
    </div>
    <?php 
      endforeach;
    ?>
  </div>
</body>
</html>