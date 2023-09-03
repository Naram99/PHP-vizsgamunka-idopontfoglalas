<?php
require_once './header.php';
require_once './idopontfoglalas/idopontFunction.php';
require_once './config/db.php';

//Ha nincs bejelentkezve, akkor átirányítja oda
if(!isset($_SESSION['user_name'])) {
  header('location: ./login/login.php');
}

//Ha még nem választott stúdiót, akkor átirányítja oda
if(isset($_GET['date']) && isset($_GET['std'])) {

  //dátum és stúdió kinyerése az URL címből
  $date = explode('-', $_GET['date']);
  $aktEv = (int) $date[0];
  $aktHonap = (int) $date[1];
  $nap = (int) $date[2];
  $std = $_GET['std'];


  //Naptár korrigálása, ha nem normális értéket kap
  //Hónap és év korrigálás ha 0. vagy 13. hónapot kapna értéknek
  if($aktHonap === 13) {
    $aktHonap = 1;
    $aktEv += 1;
  } elseif ($aktHonap === 0) {
    $aktHonap = 12;
    $aktEv -= 1;
  } elseif ($nap !== (int) date('d', mktime(0,0,0, $aktHonap, $nap, $aktEv))) {
    //nap korrigálás, ha pl. február 31-et kapna értéknek
    $nap = 1;
  }
} else {
  header('location: ./preFoglalas.php');
}

//Naptár előkészítése (elseje milyen napra esik, hónapok, napok listája)
$hanyadikNap1 = (int) date('w', mktime(0, 0, 0, $aktHonap, 1, $aktEv)) === 0 ? 7 : (int) date('w', mktime(0, 0, 0, $aktHonap, 1, $aktEv));

$honapok = ['Január', 'Február', 'Március', 'Április', 'Május', 'Június', 'Július', 'Augusztus', 'Szeptember', 'Október', 'November', 'December'];

$hetNapjai = ['Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap'];

$name = $_SESSION['user_name'];
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
  <section>
    <div class="container">
      <div class="row">
        <h2 class="text-center">Időpontfoglalás</h2>
      </div>


      <!-- Eddigi foglalások kiírása -->
      <div class="row">
        <h4>Foglalásaim:</h4>
      </div>
      <?php 
        $lista = sajatFoglalas($servername, $username, $password, $dbname, $name);
        foreach($lista as $idopont1 => $idotartam):
      ?>
      <div class="row">
        <p class="col-3"><?php print($idopont1); ?></p>
        <p class="col-3"><?php print($idotartam); ?> perc</p>
      </div>
      <?php
        endforeach;
      ?>

      <div class="row">
        <h4>Szabad időpontok:</h4>
      </div>
      <div class="row text-center">
        <h4><?php print($std); ?></h4>
      </div>

      <div class="row">

        <!-- Naptár léptető gomb -->
        <a href="./idopontfoglalas.php?date=<?php print($aktEv); ?>-<?php print($aktHonap - 1); ?>-<?php print($nap); ?>&std=<?php print($std); ?>" class="btn btn-primary col-2">Előző hónap</a>

        <!-- Aktuális hónap kiírása -->
        <h4 class="text-center col-8"><?php print($aktEv.'. '.$honapok[$aktHonap - 1]); ?></h4>

        <!-- Naptár léptető gomb -->
        <a href="./idopontfoglalas.php?date=<?php print($aktEv); ?>-<?php print($aktHonap + 1); ?>-<?php print($nap); ?>&std=<?php print($std); ?>" class="btn btn-primary col-2">Következő hónap</a>

      </div>

      <!-- NAPTÁR -->
      <table class="table">
        <thead>
            <tr>
                <?php
                foreach ($hetNapjai as $napok) {
                    ?>
                    <th><?php print($napok) ?></th>
                    <?php
                }
                ?>
            </tr>
        </thead>
        <tbody>
          <tr>
          <?php
          for ($i = 1; $i < $hanyadikNap1; $i++) {
              ?>
              <td>&nbsp;</td>
              <?php
          }
          for ($i = 1; $i < 38; $i++) {
              if ($i === (int) date('d', mktime(0, 0, 0, $aktHonap, $i, $aktEv))) {
                  ?>
                  <td class="<?php 

                            //naptár színeinek kialakítása, szürke ha régi, zöld ha van időpont
                            $regi = naptarRegi($aktEv, $aktHonap, $i);
                            $vanSzabad = naptarSzabadIdopontok($servername, $username, $password, $dbname, $aktEv, $aktHonap, $i, $std);
                            if($regi) {
                              print('text-bg-secondary');
                            } elseif ($vanSzabad) {
                              print('text-bg-success');
                            }
                            ?>"><a href="./idopontfoglalas.php?date=<?php print($aktEv.'-'.$aktHonap.'-'.$i); ?>&std=<?php print($std); ?>" style="color: inherit; text-decoration: none;"><?php print($i); ?></a></td>
                  <?php
              } else {
                  ?>
                  <td>&nbsp;</td>
                  <?php
              }
              if ((int) date('w', mktime(0, 0, 0, $aktHonap, $i, $aktEv)) === 0) {
                  ?>
              </tr><tr>
                  <?php
              }
              //Naptár sorainak korrigálása
              if ((int) date('w', mktime(0, 0, 0, $aktHonap, $i, $aktEv)) === 0 
                      && ($i !== (int) date('d', mktime(0, 0, 0, $aktHonap, $i, $aktEv)) 
                      || $i === 31 
                      || ($i === 30 && 31 !== (int) date('d', mktime(0, 0, 0, $aktHonap, 31, $aktEv))))) {
                  break;
              }
          }
          ?>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
  <section>
    <div class="container">
      <h2 class="row">Szabad időpontok:</h2>

      <!-- FOGLALÓ FORM KEZDETE -->
      <form action="" method="POST">
      <?php
        
        //szabad időpontok kilistázása
        $list = szabadIdopontok($servername, $username, $password, $dbname, $aktEv, $aktHonap, $nap, $std);
        if($list) {
          foreach($list as $id => $idopont) {
      ?>
        <!-- szabad időpontok megjelenítése input radio-ként -->
        <label for="time-<?php print($id); ?>" class="label"><?php print($idopont); ?></label>
        <input type="radio" id="time-<?php print($id); ?>" name="idopont" value="<?php print($id); ?>" required>
        <br>
      <?php
          }
        }
      ?>
        <h2 class="row">Választható csomagok:</h2>
      <?php

        //választható programok kilistázása
        $idotartamLista = idotartamok($servername, $username, $password, $dbname);
          foreach($idotartamLista as $values) {            
      ?>
        <div class="row">
          <!-- választható programok megjelenítése input radio-ként -->
          <label for="prog-<?php print($values['id']); ?>" class="label"><?php print($values['tipus']); ?>:</label>
          <input type="radio" id="prog-<?php print($values['id']); ?>" name="program" value=<?php print($values['idotartam']); ?> required>
          <p><?php print($values['leiras'].'. '.$values['idotartam'].' perc'); ?></p>
        <?php
              print('</div><br>');
            }
        ?>
        <button type="submit" class="btn btn-primary row">Foglalás</button>
        </div>
      </form>
      <?php 
        //Foglalás elindítása
        if(isset($_POST['idopont']) && isset($_POST['program'])) {
          $prog = $_POST['program'];
          $foglaltIdopont = $_POST['idopont'];

          $foglalas = foglalas($servername, $username, $password, $dbname, $prog, $foglaltIdopont, $name);
          if(!$foglalas) {
            print('<div class="row"><p class="btn btn-danger">A megadott időpontra nem lehet ilyen hosszú programot lefoglalni.</p></div>');
          }
        }
      ?>
    </div>
  </section>
</body>
</html>