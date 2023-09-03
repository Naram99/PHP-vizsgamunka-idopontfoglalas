<?php
session_start();
require_once '../config/db.php';
require_once './loginFunction.php';

//email és jelszó ellenőrzése
if(isset($_POST['email']) && isset($_POST['password'])) {

  //email ellenőrzése, hogy helyes-e a formátum
  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '';
  $password1 = $_POST['password'];

  $account = loginF($email, $password1, $servername, $username, $password, $dbname);

  if($account === 'admin') {
    header('location: ../admin.php');
  } else {
    header('location: ../idopontfoglalas.php');
  }
}
