<?php

/**
 * Criação de constantes, arrays, classes annônimas "globais"
 * e carregamento da função autoload.
 * PHP version 7.
 *
 * @package   bootstrap.
 * @author
 * @license
 */

define('ROOT',    dirname(dirname(__FILE__)));
define('WEBROOT', dirname(dirname(dirname(__FILE__))));
define("NSROOT", "pureflux");
define('DS',      DIRECTORY_SEPARATOR);


require_once ROOT . '/core/scripts/Autoload.php';
require_once ROOT . '/bootstrap/boot.php';
