<?php

//szabad időpontok listázása
function szabadIdopontok($servername, $username, $password, $dbname, $aktEv, $aktHonap, $nap, $std) {

  //visszatérő tömb létrehozása
  $list = [];

  //csatlakozás az adatbázishoz
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  //A korábban megadott stúdió ID-jának kinyerése
  $sql1 = 'SELECT id FROM studio WHERE nev = "'.$std.'"';
  $result1 = mysqli_query($conn, $sql1);
  if($result1){
    $result2 = mysqli_fetch_assoc($result1);
    $studioId = (int) $result2['id'];

    //A stúdió ID és a dátum alapján a szabad időpontok listázása
    if($studioId) {
    
      $sql = 'SELECT id, idopontok FROM idopont WHERE (CAST(idopontok AS DATE) = "'.$aktEv.'-'.$aktHonap.'-'.$nap.'" AND studio_id = '.$studioId.' AND foglalt = 0) ORDER BY idopontok';
  
      $result = mysqli_query($conn, $sql);
      if($result){

        //a kapott értékeket asszociatív tömbbe rendezzük
        while($array = mysqli_fetch_array($result)){
          $list[$array['id']] = $array['idopontok'];
        }
      }
    }
  }

  //a kinyert értékek visszaküldése, csatlakozás lezárása
  mysqli_close($conn);
  return $list;
}

//van-e szabad időpont az adott napon?
function naptarSzabadIdopontok($servername, $username, $password, $dbname, $aktEv, $aktHonap, $i, $std) {

  //visszatérési érték létrehozása
  $vanSzabad = false;

  //csatlakozás
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  //stúdió ID kinyerése
  $sql1 = 'SELECT id FROM studio WHERE nev = "'.$std.'"';
  $result1 = mysqli_query($conn, $sql1);
  if($result1){
    $result2 = mysqli_fetch_assoc($result1);
    $studioId = (int) $result2['id'];

    if($studioId) {
    
      //szabad időpontok keresése
      $sql = 'SELECT id, idopontok FROM idopont WHERE (CAST(idopontok AS DATE) = "'.$aktEv.'-'.$aktHonap.'-'.$i.'" AND studio_id = '.$studioId.' AND foglalt = 0)';
  
      $result = mysqli_query($conn, $sql);
      if($result){
        //ha van legalább egy is, akkor igaz lesz a visszatérési érték
        if(mysqli_num_rows($result) > 0){
          $vanSzabad = true;
        }
      }
    }
  }

  //visszatérési érték, csatlakozás lezárása
  mysqli_close($conn);
  return $vanSzabad;
}

//az adott nap múltbéli dátum-e?
function naptarRegi($aktEv, $aktHonap, $i) {
  $regi = false;

  //mai dátum létrehozása
  $ma = strtotime(date('Y-m-d'));

  //keresett dátum létrehozása
  $keresettDatum = strtotime($aktEv.'-'.$aktHonap.'-'.$i);

  //különbség
  $diff = ($keresettDatum - $ma);

  //ha az érték negatív, akkor a megadott dátum múltbéli, ekkor igaz a visszatérési érték
  if ($diff < 0) {
    $regi = true;
  }

  return $regi;
}

//a választható fotózási programok listázása
function idotartamok($servername, $username, $password, $dbname) {

  //visszatérő tömb létrehozása
  $list = [];

  //csatlakozás
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  $sql = 'SELECT * FROM idotartam';
  $result = mysqli_query($conn, $sql);

  //a kapott értékek többdimenziós tömbbe rendezése
  if($result) {
    while($rows = mysqli_fetch_assoc($result)) {
      $list[] = $rows;
    }
  }

  //visszatérési érték, csatlakozás lezárása
  mysqli_close($conn);
  return $list;
}

//a kiválasztott időpont lefoglalása
function foglalas($servername, $username, $password, $dbname, $prog, $foglaltIdopont, $name) {

  //visszatérési érték
  $rendben = false;

  //csatlakozás
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  //ellenőrizzük, hogy foglalható-e az adott időpont
  $foglalhato = foglalasCheck($conn, $foglaltIdopont, $prog);
  if($foglalhato) {

    //a kiválasztott időpontnál az adatbázisban a mezőket frissítjük (foglalt, ügyfél neve, időtartam) a megadott adatokra
    $sql = 'UPDATE idopont SET foglalt = 1, ugyfel_nev = "'.$name.'", idotartam = '.$prog.' WHERE id = '.$foglaltIdopont;

    $siker = mysqli_query($conn, $sql);
    if($siker) {
      $rendben = true;
    }
  }
  

  //csatlakozás lezárása
  mysqli_close($conn);
  return $rendben;
}

//a foglalt időpont és a többi időpont közötti átfedés ellenőrzése
function foglalasCheck($conn, $foglaltIdopont, $prog) {

  $foglalhato = false;

  //foglalt időpont kiválasztása
  $sql1 = 'SELECT idopontok, studio_id FROM idopont WHERE id = '.$foglaltIdopont;

  $foglalas = mysqli_query($conn, $sql1);
  if(mysqli_num_rows($foglalas) === 1){
    $foglalasId = mysqli_fetch_row($foglalas);
    if($foglalasId){

      //foglalás és stúdió kiválasztása
      $foglalas1 = $foglalasId[0];
      $foglalasStudioId = (int) $foglalasId[1];

      //foglalható-e ilyen hosszú időtartamra a stúdió? Ha nem, a visszatérési érték hamis lesz
      $denySql = 'SELECT idopontok FROM idopont WHERE TIMESTAMPDIFF(MINUTE, "'.$foglalas1.'", idopontok) < '.$prog.' AND TIMESTAMPDIFF(MINUTE, "'.$foglalas1.'", idopontok) > 0 AND studio_id = '.$foglalasStudioId;

      $deny = mysqli_query($conn, $denySql);
      if($deny){
        if(mysqli_num_rows($deny) < 1) {
          
          //ha a kiválasztott program hosszú, a megadott időtartam alatt a többi időpontot is foglaltnak jelzi
          $updateSql = 'UPDATE idopont SET foglalt = 1 WHERE TIMESTAMPDIFF(MINUTE, idopontok, "'.$foglalas1.'") < '.$prog.' AND TIMESTAMPDIFF(MINUTE, idopontok, "'.$foglalas1.'") > 0 AND studio_id = '.$foglalasStudioId;

          mysqli_query($conn, $updateSql);
          $foglalhato = true;
        }
      }
    }
  }

  return $foglalhato;
}

//saját foglalások listázása
function sajatFoglalas($servername, $username, $password, $dbname, $name) {

  //visszatérő tömb létrehozása
  $sajatFoglalas = [];

  //csatlakozás
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  //az ügyfél saját időpontjainak keresése felhasználónév alapján
  $sql = 'SELECT idopontok, idotartam FROM idopont WHERE ugyfel_nev = "'.$name.'"';


  $result = mysqli_query($conn, $sql);
  if($result) {

    //asszociatív tömbbe rendezzük a kapott értékeket
    while($foglalas = mysqli_fetch_assoc($result)) {
      $sajatFoglalas[$foglalas['idopontok']] = $foglalas['idotartam'];
    }
  }

  //értékek visszaküldése, csatlakozás lezárása
  mysqli_close($conn);
  return $sajatFoglalas;
}