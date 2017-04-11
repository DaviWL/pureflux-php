<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace pureflux\core;

/**
 * Mantem um ou mais arquivos de log.
 * @namespace pureflux\core.
 * @package core
 * @version prealfa
 * @author leik4
 */
class Logger 
{
    /**
     * @var string.
     * Arquivo e diretório de log.
     */
    static $logFile = ROOT."/log/log.log";
    
    //function __construct($logFile)
    //{
    //}
    
    /**
     * Seta log com outro caminho/nome do arquivo.
     * @param type $logFile Novo caminho/nome de arquivo.
     * @param type $txt Descrição de evento, para o caso de log momentâneo.
     */
    static function setLog($logFile, $txt = "")
    {
        self::$logFile = $logFile;
        self::log($txt);
        return;
    }
    
    /**
     * Registra um evento.
     * @param string $txt Descrição do evento
     * @return boolean
     */
    static function log(string $txt)
    {
        $ok = false;
        if($txt != '') {
           
            $fh = fopen(self::$logFile, 'a');
            fwrite($fh,date('Y-m-d H:i:s')." ".$txt."\n");
            fclose($fh);
            $ok = true;
        }
        return $ok;
    }
    
    /**
     * Faz um backup do log atual e depois apaga o arquivo.
     * @param bool $bkp Apagar backup também.
     * @return bool Realizado com sucesso.
     */
    static function clear(bool $bkp = false)
    {
        //Verifica existência dos arquivos antes de apaga-los
        //para evitar erros.
        
        if ($bkp) {
            if (file_exists(self::$logFile.".bkp")) {
                unlink(self::$logFile.".bkp");
            }
        } else {
            self::makeBkp();
        }
        if (file_exists(self::$logFile)) {
            unlink(self::$logFile);
        }
        return true;
    }
    
    /**
     * Faz backup do arquivo de log.
     */
    static function makeBkp()
    {
        copy(self::$logFile, self::$logFile.".bkp");
        return;
    }
    
    static function bkpRecover()
    {
        copy(self::$logFile.".bkp", self::$logFile);
        return;
    }
    
}
  