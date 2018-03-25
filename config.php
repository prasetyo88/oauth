<?php
require_once 'sdk/autoload.php'; // load file autoload from facebook

$appId     = "396425950819747"; // replace dengan app id yang kamu dapatkan di facebook developer
$appSecret = "2eaa49d07220e861215bb35fc0fb9184"; // replace dengan secret key yang kamu dapatkan di facebook developer

class db {

  function __construct() {
    $dbhost = "localhost"; // replace dengan database host kamu
    $dbuser = "root"; // replace dengan databae user kamu
    $dbpass = "P@ssw0rd"; // replace dengan database pass kamu
    $dbname = "oauth"; // replace dengan database name kamu
    $this->mysqli = new mysqli($dbhost,$dbuser,$dbpass,$dbname );
    if(mysqli_connect_error()) {
      die("Tidak Bisa Konek Ke Database Karena : ". mysqli_connect_errno());
    }
  }

  function redirect($url) {
    echo "<script type='text/javascript'>window.top.location='$url';</script>";
  }
} 