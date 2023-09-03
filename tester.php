<?php 
require_once './idopontfoglalas/idopontFunction.php';
require_once './admin/adminPortfolioFunctions.php';
require_once './config/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
  <?php 
    idopontLista($servername, $username, $password, $dbname);
  ?>
</body>
</html>