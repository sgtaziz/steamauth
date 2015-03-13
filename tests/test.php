<?php 

require_once __DIR__ . '/../vendor/autoload.php';

use sgtaziz\SteamAuth\SteamAuth;

$test = new SteamAuth;
$test->Auth('server2.horizonservers.net');
