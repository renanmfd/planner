<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'php/classes/File.class.php';
require_once 'php/classes/Database.class.php';
require_once 'php/classes/User.class.php';
require_once 'php/classes/Entry.class.php';
require_once 'php/classes/Theme.class.php';
require_once 'php/krumo/Krumo.class.php';

require_once 'php/bootstrap.php';

// GLOBAL VARIABLES.

// Prepare database variables.
$config = get_config();
$db = new Database($config->host, $config->username,
                   $config->password, $config->database);

resolve_request();
//require_once 'test.php';
