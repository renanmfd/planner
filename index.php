<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'php/classes/File.class.php';
require_once 'php/classes/Database.class.php';
require_once 'php/classes/Theme.class.php';
require_once 'php/krumo/Krumo.class.php';

require_once 'php/bootstrap.php';

$GLOBALS['config'] = get_config();

resolve_request();
