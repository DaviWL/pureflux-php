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

/**
 * Rota para controllers e scripts.
 * @namespace pureflux\core
 * @package core
 * @version prealfa
 * @author leik4
 */
class Route
{
    /**
     * @var array Propriedades da rota.
     */
    protected $properties = array(
        "app"       =>"", 
        "controller"=>"",
        "action"    =>"",
        "parameters"=>[],
        "script"    =>"");
    
    /**
     * Construtor.
     * @param array rotas Nome da propriedade e seu valor.
     */
    public function __construct($query = null) //array $rotas)
    {
        if (is_string($query)) {
            $this->setRoute($query);
        } elseif (is_array($query)) {
            $this->setRouteList($query);
        }
        return;
    }
    
    /**
     * Seta as propriedes que determinam o caminho a ser percorrido
     * pelo Roteador.
     * @param string $name Nome da propriedade.
     * @param string $value Valor da propriedade.
     * @return
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->properties)) {
            $this->$name = $value;
        }
        return;
    }

    /**
     * Retorna uma propriedade da rota.
     * @param string $name Nome da propriedade protected ou private.
     * @return type Mixed.
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->properties)) {
            return $this->$name;
        }
        return null;
    }
    
    /**
     * Retorna todas as propriedades da rota.
     * @return array $all Lista com todas as propriedades.
     */
    public function getAll(): array
    {
        return $this->properties;
    }

    /**
     * Converte as propriedades da rota.
     * @return string
     */
    public function __toString()
    {
        $str = '';
        foreach ($this->properties as $key => $value) {
            
            if (is_array($value)) {
               foreach ($value as $param) {
                    $str.= $param."/";
               }            
            } else {
                $str.= $value."/";
            }
        }
        $str = rtrim($str,"/");
        return $str;

    }
    
    /**
     * Registra dados da rota no formato string.
     * @param string $queryStr Dados da rota.
     * @return bool Retorna se a rota foi bem configurada.
     */
    public function setRoute(string $queryStr): array
    {
        $query = explode("/",$queryStr);
        $this->setRouteList($query, false);
        return $this->properties;
    }
    
    /**
     * Registra dados da rota no formato array.
     * @param array $query Dados da rota.
     * @return bool Retorna se a rota foi bem configurada.
     */
    public function setRouteList(array $query, $check = true)
    {
        foreach ($this->properties as $id => $value) {
            if ($check and key[$query] != $id) {
                continue;
            }
            $this->properties[$id] = next($query);
        }
        return;
    }
    
}