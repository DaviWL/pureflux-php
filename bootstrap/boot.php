<?php

/**
 * Inicia o framework e executa os apps instalados e ativos.
 */
namespace pureflux\core;

$app    = Application::getInstance();
$config = $app->newObject("pureflux\core", "Config");
$script = $app->newObject("pureflux\core", "ScriptCore");
$router = $app->newObject("pureflux\core", 'Router');
//$viewC  = $app->newObject("pureflux\core", "ViewCompiler");
$log    = $app->newObject("pureflux\core", 'Logger');


/**
 * Carregamento da configuração do framework (id "core") e de 
 * outros objetos iniciais.
 */
$app->Config->addSource("core", ROOT.DS."config", true);
$app->Config->loadID("core");


/**
 *  Load de scripts
 */
$app->Logger->log("Scripts de inicialização ...ok!");


/**
 * Desenvolvimento: Testando a classe ViewCompiler
 */
//require_once ROOT . '/bootstrap/tryView.php';

$tpl = file_get_contents(ROOT."/tmp/teste.tpl.html");
echo $tpl;

/** 
 * Rotas
 */
$app->Router->init();

