<?php

function portfolioF($servername, $username, $password, $dbname) {

  $fotonev = [];

  //csatlakozás
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  //hibaüzenet, ha nem sikerült a csatlakozás
  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  //stúdiónevek kiválasztása
  $sql = 'SELECT nev FROM studio';
  $result = mysqli_query($conn, $sql);
  if($result) {
    while($row = mysqli_fetch_array($result)) {
      $studionev = $row['nev'];

      //a fotók neveit a stúdiókhoz kapcsoljuk
      $sql2 = "SELECT fotonev FROM portfolio AS p JOIN studio AS s ON(p.studio_id = s.id) WHERE s.nev = '$studionev'";

      $fotoResult = mysqli_query($conn, $sql2);
      if($fotoResult) {
        while($fotoRow = mysqli_fetch_array($fotoResult)) {

          //a stúdiók neveit és a hozzájuk tartozó fotókat többdimenziós tömbben adja vissza
          $fotonev[$studionev][] = $fotoRow['fotonev'];
        }
      }
    }
  }

  //szétkapcsolás
  mysqli_close($conn);

  return $fotonev;
  
}

//stúdiók listázása
function studioListMain($servername, $username, $password, $dbname) {

  //visszatérő tömb létrehozása
  $list = [];

  //csatlakozás
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  //stúdiók adatainak kinyerése
  $sql = 'SELECT nev, leiras FROM studio';
  $result = mysqli_query($conn, $sql);
  if($result) {

    //a kinyert adatokat asszociatív tömbbe tesszük
    while($rows = mysqli_fetch_assoc($result)) {
      $list[$rows['nev']] = $rows['leiras'];
    }
  }

  //csatlakozás zárása, visszatérés
  mysqli_close($conn);
  return $list;
}