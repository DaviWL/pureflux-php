<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace pureflux\core;

/**
 * Description of ExceptionPure
 * @namespace neoidea\core
 * @package   core
 * @version prealfa
 * @author leik4
 */
class ExceptionPure extends \Exception
{
    /**
     * Registrar exceção no arquivo de log?
     * @var bool
     */
    protected $log = true;
    
    public function __construct($msg)
    {
        //parent::construct($msg);
        self::exceptionAlert($msg);
        self::logFailure($msg);
    }
    
    
    function logFailure(string $msg)
    {
        global $log;
        $log->log($msg);
        return;
    }
    
    function exceptionAlert($msg)
    {
        echo '<script type="text/javascript" language="javascript">\n';
        echo 'alert('.$msg.');\n';
        echo '</script>\n';
        return;
    }
}
