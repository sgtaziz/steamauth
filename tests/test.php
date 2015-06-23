<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use Sgtaziz\SteamAuth\SteamAuth;

$test = new SteamAuth;
$test->Auth(url(''));
