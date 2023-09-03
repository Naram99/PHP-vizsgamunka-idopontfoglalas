<?php

function portfolioList($servername, $username, $password, $dbname) {
  
  
}

function portfolioUpload($servername, $username, $password, $dbname, $studioId, $picname) {
  
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  $sql = 'INSERT INTO portfolio (`fotonev`, `studio_id`) VALUES (?,?)';
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'si', $picname, $studioId);
  mysqli_stmt_execute($stmt);



  mysqli_close($conn);
}

function studioList($servername, $username, $password, $dbname) {

  $list = [];

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  $sql = 'SELECT id, nev FROM studio';
  $result = mysqli_query($conn, $sql);
  if($result) {
    while($rows = mysqli_fetch_assoc($result)) {
      $list[$rows['id']] = $rows['nev'];
    }
  }

  mysqli_close($conn);
  return $list;
}

function studioInsert($servername, $username, $password, $dbname, $studionev, $studioleiras, $fotosneve) {
  
  if(strlen($studionev) > 0 && strlen($studioleiras) > 0) {

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if(!$conn) {
      die('Connection failed: ' . mysqli_connect_error());
    }

    $sql = 'INSERT INTO studio (`nev`, `leiras`, `fotosNeve`) VALUES (?,?,?)';
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sss', $studionev, $studioleiras, $fotosneve);
    mysqli_stmt_execute($stmt);

    mysqli_close($conn);
  }
}

function studioDelete($servername, $username, $password, $dbname, $studioId) {

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  $sql = 'DELETE FROM studio WHERE id = '.$studioId;
  mysqli_query($conn, $sql);
}

function ujIdopont($servername, $username, $password, $dbname, $ujidopont, $studio) {
  
  $foglalt = 0;

  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  $sql = 'INSERT INTO idopont (`idopontok`, `foglalt`, `studio_id`) VALUES (?,?,?)';
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, 'sis', $ujidopont, $foglalt, $studio);
  mysqli_stmt_execute($stmt);

  mysqli_close($conn);
}

//foglalt időpontok listázása
function idopontLista($servername, $username, $password, $dbname) {

  //visszatérési érték létrehozása
  $list1 = [];

  //csatlakozás
  $conn = mysqli_connect($servername, $username, $password, $dbname);

  if(!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
  }

  //foglalt időpontok kiválasztása
  $sql = 'SELECT idopontok, ugyfel_nev, idotartam FROM idopont WHERE ugyfel_nev IS NOT NULL ORDER BY idopontok';

  $result = mysqli_query($conn, $sql);
  if($result) {
    $i = 0;
    while($list = mysqli_fetch_assoc($result)){

      $list1[$i] = $list['ugyfel_nev'].', '.$list['idopontok'].', '.$list['idotartam'];
      $i++;
    }
  }

  //csatlakozás zárása, visszatérés
  mysqli_close($conn);
  return $list1;
}