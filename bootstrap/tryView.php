<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace pureflux\core;

global $app;
global $viewC;

/**
 * Carrega template necessário.
 */
$app->ViewCompiler->loadTemplate(ROOT."/tmp/teste.tpl.html");
$data = array( "title" => "Template testes",
            "username" => "leik4",
            "age"      => "14",
            "work"     => "developer");

$viewC->parse($data);

/**
 * Gera saida do template interpretado.
 */
//$output = $viewC->output();
//echo $output;
