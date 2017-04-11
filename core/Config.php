<?php

/* 
 * Copyright (c) 2016, euloucopoeta
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
 * Localiza, abre e armazena arquivos de configurações de toda a aplicação
 * e Framework.
 * 
 * @namespace neoidea\core
 * @package core
 * @version prealfa
 * @author leik4
 */
class Config extends Foundation\Singleton
{   
    /**
     * @var array Lista de arquivos
     */
    protected $fileList = array();
    
    /**
     * @var Lista de diretórios.
     */
    protected $sourceList = array();
    
     /**
	 * Instância da classe.
	 * @access protected static
	 */
    protected static $instance;
    
    /**
	 * Cria e retorna uma instância única da classe.
	 * @return Registry.
	 */
	public static function getInstance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
    
    /**
     * Adiciona um diretório (source) ao sourceList. O diretório deve
     * possuir arquivos de configuração.
     * @param string $id Identidade do diretório adicionado.
     * @param string $source Diretório com arquivos de configuração.
     * @param bool $parse Executar o método addSourceFiles.
     * @throws \Exception Caso o diretório (source) não exista.
     */
    public function addSource(string $id, string $source, bool $parse = false)
    {
        if (!file_exists($source)) {
            return false;
            //throw new \Exception("source fornecido não existe");
        }
        $this->sourceList[$id] = $source;
        if ($parse) {
            $this->addSourceFiles($id);
        }
        return;
    }
    
    /**
     * Adiciona todos os arquivos de determinados diretórios.
     * @param string $id Identidade dos diretórios desejados.
     */
    public function addSourceFiles(string $id) 
    {
        foreach ($this->sourceList as $source) {
            
            if (!is_dir($source)) {
                continue;
            }
            
            $dirFiles = scandir($source);
            foreach ($dirFiles as $item) {
                
                if (is_dir($item)) {
                    continue;
                }
                
                $file = $source.DS.$item;
                $fileName = str_replace(strrchr($item, "."),"",$item);
                $this->addFile($id, $fileName, $file);
            }
       }
    }
    
    /**
     * Adiciona um arquivo na fileList.
     * @param string $id Identidade do arquivo desejado.
     * @param string $fileName Nome do arquivo (outra forma de identificador.
     * @param type $file
     * @param type $value
     * @return bool
     */
    function addFile($id, $fileName, $file, $value = null): bool
    {
        if (!is_file($file)) {
            return false;
        }       
        $this->fileList[$id][$fileName][$file] = $value;
        return true;
    }

    /**
     * Retorna um arquivo de configuração e iinclui ou não seu conteúdo 
     * formatado de acordo com seu tipo (json, yaml, xml, ini, php...)
     * @param string $id Identidade do arquivo desejado.
     * @param string $fileName Nome do arquivo (outra forma de identificador.
     * @param bool Load. Carrega arquivo formatado.
     * @return array ou string.
     */
    function getFile($id, $fileName, $load = false)
    {
        if ($load) {
            $this->loadFile($id, $fileName);
        }
        return $this->fileList[$id][$fileName];
    }
    
    public function getSetting($id, $fileName)
    {
        if (!key_exists($id, $this->fileList)) {
            return false;
        }
        $fileItem = $this->fileList[$id][$fileName];
        $setting  = $this->fileList[$id][$fileName][ key($fileItem) ];

        if ($setting == null) {
            $this->loadFile($id, $fileName);
        }
        return $setting;
    }
    
    public function get($id, $fileName)
    {
        return $this->getSetting($id, $fileName);
    }
    
    /**
     * Retorna uma lista de arquivos com o mesmo ID.
     * @param string $id Identidade do arquivo desejado.
     * @return array Lista de arquivos.
     */
    public function getID($id)
    {
        if (!key_exists($id, $this->fileList)) {
            return false;
        }
        return $this->fileList[$id];
    }
    
    /**
     * Carrega um arquivo de configuração.
     * @param string $id Identidade do arquivo desejado.
     * @param string  $fileName Nome do arquivo (outra forma de identificador.
     * @return string  Conteúdo interprertado do arquivo de configuração.
     */
    function loadFile($id, $fileName)
    {
        $fileItem = $this->fileList[$id][$fileName];
        
        $file = key($fileItem);
        $fileType = trim(substr(strrchr($file, "."), 1));
        
        switch ($fileType) {
            case 'json':
                $content    = file_get_contents($file);
                $loadedFile = json_decode($content, true);
                break;
            case 'ini':
                $content    = file_get_contents($file);
    			$loadedFile = parse_ini_file($content);
                break;
        }
        $this->fileList[$id][$fileName][$file] = $loadedFile;
        return $loadedFile;
    }
    
    /**
     * Carrega todos os arquivos da identificação.
     * @param string $id Identidade do arquivo desejado.
     * @return string  Conteúdo interprertado do arquivo de configuração.
     */
    function loadID($id)
    {
        foreach ($this->fileList as $key => $value) {
            if ($key == $id) {
                $this->loadFile($id, key($value));
            }
        }
        return $this->getID($id);
    }
}
