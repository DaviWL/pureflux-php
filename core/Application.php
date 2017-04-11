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
 * Aplicação.
 * Registro de todos os objetos do Framework e Aplicativo. Um acesso global à
 * objetos, durante a vída do aplicativo, incluindo os processos de cache.
 * Também tem um método para criação de objetos dinâmicos, não previstos no
 * Framework.
 * 
 * @namespace neoidea\core
 * @package   core
 * @version prealfa
 * @author leik4
 */
class Application extends Foundation\Registry
{
    /**
	 * Instância da classe.
	 * @access private
	 */
    protected static $frameworkID = "PHPKninght alpha.0.05";

    /**
	 * Instância da classe.
	 * @access private
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
	 * Cria e retorna uma instância única da classe.
	 * @return Registry.
	 */
	public static function getFrameworkID() {
		return self::$frameworkID;
    }    
}
