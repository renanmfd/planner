<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'php/classes/Database.class.php';
require_once 'php/classes/File.class.php';
require_once 'php/classes/User.class.php';

require_once 'php/db_user.php';

// session_start inicia a sessão
session_start();

resolve_user();
