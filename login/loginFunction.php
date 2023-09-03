<?php

function loginF($email, $password1, $servername, $username, $password, $dbname) {
  //email és jelszó hosszának az ellenőrzése
  if(strlen($email) > 0 && strlen($password1) > 0) {

    //csatlakozás az adatbázishoz
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    //csatlakozás ellenőrzése
    if(!$conn) {
      die('Connection failed: ' . mysqli_connect_error());
    }

    //SQL lekérdezés
    $sql = "SELECT id, nev, email, jelszo FROM ugyfel WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    //lekérdezés sikerességének ellenőrzése
    if($result) {

      //találatok számának ellenőrzése, ha több van mint 1, az nem elfogadható
      if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        //jelszó összehasonlítása a tárolt hash-el
        if(password_verify($password1, $row['jelszo'])) {
          unset($_SESSION['errorMessage']);
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['user_name'] = $row['nev'];

          return $_SESSION['user_name'];
          

        } else {
          $_SESSION['errorMessage'] = 'Az email cím, vagy a jelszó nem megfelelő';
          header('location: ./login.php');
        }
      } else {
        $_SESSION['errorMessage'] = 'Az email cím, vagy a jelszó nem megfelelő';
        header('location: ./login.php');
      }
    } else {
      $_SESSION['errorMessage'] = 'Ismeretlen hiba';
      header('location: ./login.php');
    }

    mysqli_close($conn);
  } else {
    $_SESSION['errorMessage'] = 'Az email cím, vagy a jelszó nem megfelelő';
    header('location: ./login.php');
  }
}