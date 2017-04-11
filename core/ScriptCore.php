<?php

/*
 * Copyright (c) 2017, euloucopoeta
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * * Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * * Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace pureflux\core;
use pureflux\core\Foundation as Foundation;

/**
 * ScriptCore.
 * Executa scripts organizados e classificados por função ou contexto. Suporta
 * a inclusão de todos os scripts de um diretório especificado.
 * Também inclui métodos para o tratamento de scripts em geral
 * @namespace neoidea\core
 * PHP version 7.
 *
 * @package   bootstrap.
 * @author    Think Big S.A. <sys@thinkbig.com>.
 * @license   GNU LGPL  Version 3, 29 June 2007 <http://fsf.org/>.
 * @link      http://neoidea.org/
 */
class ScriptCore extends Foundation\Singleton
{     
    /**
     * Lista de scripts em fila de execução.
     */
    private $scriptList = array();
    
     /**
     * Ambiente de execução dos scripts cadastrados.
     */
    private $environment;
    
    /**
     * Instância da classe, uma singleton.
     */
    protected static $instance;

    /**
     * Cria a única instância da classe.
     * @return ScriptCore instância
     */
    public static function getInstance()
    {
        if( !isset( self::$instance ) ) {
            $obj = __CLASS__;
            self::$instance = new $obj;
        }
        return self::$instance;
    }
    
    /**
     * Configura ambiente para que os scripts possam ter acesso ao
     * aplicativo (Application).
     * @param \pureflux\core\Application $env Ambiente da aplicação para
     * uso direto pelos scripts a serem executados.
     */
    function setEnv(Application &$env) {
        $this->enviroment = $env;
        return;
    }
    
    /**
     * Configura ambiente para que os scripts possam ter acesso ao
     * aplicativo (Application).
     * @param \pureflux\core\Application $env Ambiente da aplicação para
     * uso direto pelos scripts a serem executados.
     */
    function getEnv() {
        return $this->enviroment;
    }
    
    /**
     * addScript.
     * Adiciona um script na scriptList.
     * @param string $id Identificador, propriedade de uma certa ID.
     * @param string $script Nome e caminho do script no servidor.
     * @return int Total atualizado de scripts na $scriptList.
     */
    function addScript(string $id, string $script): int
    {
        if (is_dir($script) or !file_exists($script)) {
            return -1;
        }
        $this->scriptList[$id][] = $script;
        return count($this->scriptList);
    }
        
    
    /**
     * Remove todos os caminhos incluidos na propriedade sourceList com
     * determinada ID.
     * @param type $id Filtro para determinada ID.
     * @return bool
     */
    function removeScript(string $id): bool
    {
        $ok = false;
        if (key_exists($id, $this->scriptList)) {
            unset($this->scriptList[$id]);
            $ok = true;
        }
        return $ok;
    }
    
    /**
     * addSource.
     * Adiciona um path na sourceList.
     * @param string $id Identificador, propriedade de uma certa ID.
     * @param string $script Caminho para todos seus script no servidor.
     * @return int Total atualizado de sources na $sourceList.
     */    
    function addSource(string $id, string $path, bool $addFiles = false)
    {        
        if (!is_dir($path) or !file_exists($path)) {
            return -1;
        }
        $source = rtrim($path, ".");
        print_r($source); echo "<br><br>";
        
        $this->sourceList[$id][] = $source;
        if ($addFiles) {
            $this->addSourceScripts($id);
        }
        
        return count($this->sourceList);
    }// addSource
    
    /**
     * addSourceScripts.
     * Adiciona toda os arquivos de cada sourceList na scriptList de
     * determinada ID, opcionalmente.
     * @param string $id Identificador do contexto dos scripts.
     * @return int Novo total de scripts da $scriptList.
     */
    function addSourceScripts(string $id, string $path = null): int
    {
        if (is_dir($path)) {
            $this->addSource($id, $path);
        }
        
        foreach ($this->sourceList as $path) {
            $path = array_shift($path);
            $dirFiles = scandir($path);
            
            foreach ($dirFiles as $fileName) {
                
                $file = $path."/".$fileName;
                $fileType = trim(substr(strrchr($file, "."), 1));
    
                if (is_file($file) and strtolower($fileType) == "php" ) {
                    $this->addScript($id, $file);
                }
            }
        }
        return count($this->scriptList);
    }// addSourceScripts        
    
    /**
     * Remove todos os caminhos incluidos na propriedade sourceList com
     * determinada ID.
     * @param type $id Filtro para determinada ID.
     * @return bool
     */
    function removeSources($id = null): bool
    {
        $num = 0;
        // se id existe na sourceList, apaga apenas os itens do id.
        if ( key_exists($id, $this->sourceList)) {
            unset($this->sourceList[$id]);
            $num++;
        } elseif ($id == null) {
            // apaga todos os itens de todos os identificadores (id).
            foreach ($this->sourceList as $key => $value) {
                unset($this->sourceList[$key]);
                $num++;
            }
        }
        // num elementos sources eliminados.
        return $num;
    }//removeSources

    /**
     * Adiciona e executa um novo script que pode ou não ser filtrado
     * por uma ID.
     * @param type $script Nome do script e respectivo caminho.
     * @param type $id Filtro para determinada ID. Caso não fornecido,
     * considera-se null.
     */
    function runFile($script, $id = null): bool
    {
        $ok = $this->addScript("",$script);
        if ($ok) {
            $ok = $this->run($id);
        }
        return $ok;
    }//runFile
    
    /**
     * Executa todos os scripts da propriedade scriptList ou apenas de 
     * determinada ID.
     * @param type $id Identificação para realizar a execução.
     * @return boolean
     */
    function run($id = null)
    {
        $ok = false;
        foreach ($this->scriptList as $scripts) {
            foreach ($scripts as $key => $script) {
                
                $env = $this->getEnv();
                require_once $script;
            }
        }
    }//run    
}
