<?php
session_start();
require_once 'config.php';
$fb = new Facebook\Facebook([
  'app_id' => $appId,
  'app_secret' => $appSecret,
]);

$helper = $fb->getRedirectLoginHelper();
$loginUrl = $helper->getLoginUrl("https://". $_SERVER['SERVER_NAME'] . "/process.php",array('scope' => 'email'));
header("location: " . $loginUrl);