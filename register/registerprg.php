<?php
session_start();
require_once './registerFunction.php';
require_once '../config/db.php';

//email és jelszó ellenőrzése
if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['name'])) {

  //email ellenőrzése, hogy helyes-e a formátum
  $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : '';

  //név ellenőrzése
  $name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);

  $password1 = $_POST['password'];
  $password2 = $_POST['passwordCheck'];

  $result = registerF($name, $email, $password1, $password2, $servername, $username, $password, $dbname);

  if($result) {
    header('location: ./regSuccess.php');
  } 
  
}
