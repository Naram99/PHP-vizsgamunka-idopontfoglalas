<?php

function registerF($name, $email, $password1, $password2, $servername, $username, $password, $dbname) {

  $siker = false;

  //email és név hosszának az ellenőrzése
  if(strlen($email) > 0 && strlen($name) > 0) {

    //admin felhasználót ne lehessen regisztrálni
    if($name !== 'admin'){

      //jelszó hosszának ellenőrzése
      if(strlen($password1) > 5) {

        

        //jelszavak összehasonlítása
        if($password1 === $password2) {

          //csatlakozás az adatbázishoz
          $conn = mysqli_connect($servername, $username, $password, $dbname);

          //csatlakozás ellenőrzése
          if(!$conn) {
            die('Connection failed: ' . mysqli_connect_error());
          }

          //SQL lekérdezés, van-e már regisztrált ügyfél a megadott email címmel?
          $sql = "SELECT id, nev, email, jelszo FROM ugyfel WHERE email = '$email'";
          $result = mysqli_query($conn, $sql);

          //lekérdezés sikerességének ellenőrzése
          if($result) {

            //találatok számának ellenőrzése, ha van, az nem jó
            if(mysqli_num_rows($result) < 1) {
              
              //password hash generálása
              $passwordH = password_hash($password1, PASSWORD_DEFAULT);

              //a megadott adatok beiktatása az adatbázisba
              $sql2 = "INSERT INTO ugyfel (`nev`, `email`, `jelszo`) VALUES (?,?,?)";

              //prepared statement
              $stmt = mysqli_prepare($conn, $sql2);
              mysqli_stmt_bind_param($stmt, 'sss', $name, $email, $passwordH);
              mysqli_stmt_execute($stmt);

              //error message törlése
              unset($_SESSION['errorMessage']);
              $siker = true;

            } else {
              $_SESSION['errorMessage'] = 'A megadott email címmel már regisztráltak';
              header('location: ./register.php');
            }
          } else {
            $_SESSION['errorMessage'] = 'Ismeretlen hiba';
            header('location: ./register.php');
          }

          mysqli_close($conn);
        } else {
          $_SESSION['errorMessage'] = 'A megadott jelszavak nem egyeznek';
          header('location: ./register.php');
        }
      } else {
        $_SESSION['errorMessage'] = 'A jelszó legalább 6 karakterből kell, hogy álljon';
        header('location: ./register.php');
      }
    } else {
      $_SESSION['errorMessage'] = 'Az email cím, vagy a megadott név nem megfelelő';
      header('location: ./register.php');
    }
  } else {
    $_SESSION['errorMessage'] = 'Az email cím, vagy a megadott név nem megfelelő';
    header('location: ./register.php');
  }

  return $siker;
}