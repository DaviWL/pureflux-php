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
 * Description of Controller
 * @namespace neoidea\core
 * @package   core
 * @version prealfa
 * @author leik4
 */
class Router extends Foundation\Singleton
{
    /**
     * @var array Lista de rotas default estáticas, em arquivos
     * de configuração. 
     */
    protected $defaultRotes = [];
    
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
     * Inicializa rotas default e métodos GET, POST, FILE entre outros.
     * @global Application $app Classe core\Registro com objetos essenciais
     * ao Framework.
     * @param Rote $routes imediatas, não obrigatórias.
     * @return boolean Router inializado com sucesso ou não.
     */
    public function init(string ...$routes) 
    {
        $ok = false;
        
        // Rotas enviadas diretamente pelo init.
        foreach ($routes as $value) {
            $this->addRoute("init", $value);
        }
        
        
        /**
         * @global \core\Application $app.
         */
        global $app;
        $defaultRotes = $app->obj("Config")->getSetting("core", "routes");
        $this->defaultRotes = $defaultRotes; 
        $method = $_SERVER["REQUEST_METHOD"];
        //...
        
        if ($method == "GET") {
            $routeList = $_GET;
        }
        
        
        //$query = $_GET;
        $routeStr = "helloworld/People/newMember/verify";
        $this->addRoute("url", $routeStr);
        
        
        
        
        return $ok;
    }
    
    public function addRoute(string $id, string $routeStr, bool $check = true)
    {
        $ok = $this->checkRoute($routeStr);
        if ($ok) {
            $this->routes[$id] = new Route($routeStr);
            
            echo $this->routes[$id];
            
        }
        return $ok;
    }
    
    /**
     * Verifica validade da rota.
     * @param string $routeStr Rota no format string. 
     * @return bool A rota é valida?
     */
    public function checkRoute(string $routeStr): bool
    {
        $ok = false;
        // Rota fraguimentada num array
        $routeStr = explode("/",$routeStr);
        $app        = current($routeStr);
        $controller = next($routeStr);
        $action     = next($routeStr);
        
        // primeiro o app   
        $dir = $this->defaultRotes["apps"];
        if (!is_dir(ROOT.DS.$dir.$app)) {
            return false;
        }
        
        // segundo o controller
        if (!file_exists(ROOT.DS.$dir.DS.$app.DS."controllers".DS.$controller.".php")) {
            return false;
        }
        
        // terceiro o metodo
        $ClassName = "pureflux\\server\\".$app."\\Controllers\\".$controller;
        if (!method_exists($ClassName, $action)) {
            return false;
        }
        $ok = true;
        return $ok;
    }
}
   
