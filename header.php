<?php
session_start();
?>

<!-- Fejléc létrehozása -->
<nav class="navbar navbar-expand navbar-dark bg-dark">
  <div class="container">
    <a href="./index.php" class="navbar-brand"><img src="./assets/images/alpha.png" alt="logo" style="height: 80px;"></a>
    <ul class="navbar-nav">

      <!-- Ha admin a felhasználó, akkor a kezelőfelület megjelenik -->
      <?php 
        if(isset($_SESSION['user_name']) && $_SESSION['user_name'] === 'admin') {
          print('<li class="nav-item">
          <a href="./admin.php" class="nav-link">Admin kezelőfelület</a>
          </li>');
        }
      ?>
      <li class="nav-item">
        <a href="./portfolio.php" class="nav-link">Portfólió</a>
      </li>
      <li class="nav-item">
        <a href="./idopontfoglalas.php" class="nav-link">Időpontfoglalás</a>
      </li>
    </ul>
    <div class="nav-item">

    <!-- HA belépett már a felhasználó, akkor a felhasználónév és a kilépés gomb jelenik meg neki, ha nem, akkor a belépés -->
    <?php 
      if(isset($_SESSION['user_name'])){
        print('<p style="color: white;">'.$_SESSION['user_name'].'</p><a href="./login/logoutprg.php" class="btn btn-outline-danger">Kijelentkezés</a>');
      } else {
        print('<a href="./login/login.php" class="btn btn-danger">Belépés</a>');
      }
        
    ?>
    </div>
  </div>
</nav>