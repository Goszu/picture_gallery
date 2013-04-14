<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

// site root - needs to be configured properly
defined('SITE_ROOT') ? null :
    define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT']);

define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS.'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'functions.php');

// load core objects
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'database.php');

// load database-related classes
require_once(LIB_PATH.DS.'user.php');
require_once(LIB_PATH.DS.'item.php');

?>