<?php

$config = parse_ini_file('config.ini');
define('FEDERATED_USERNAME', $config['username']);
define('FEDERATED_PASSWORD', $config['password']);

?>