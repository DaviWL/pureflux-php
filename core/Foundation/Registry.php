<?php
namespace pureflux\core\Foundation;

/**
 * Registra objetos.
 * Armazena referências string à objetos (não ponteiros).
 * 
 * @package   pureflux\core
 * @version   prealfa
 * @author    leik4
 */
abstract class Registry
{
    /**
	 * Instância da classe.
	 * @access protected
	 */
    protected static $instance;
	
	/**
	 * Objetos armazenados na classe
	 * @access protected
	 */
    protected static $objects = array();
	
    protected function __construct() {}
	
    /**
	 * Cria e retorna uma instância única da classe.
	 * @return Registry.
	 */
	abstract static function getInstance();
    
    /**
	 * Impede que seja feito um clone o objeto.
	 * (issues an E_USER_ERROR caso um clone seja requisitado.
	 */
	public function __clone()
	{
		trigger_error( 'Cloning the registry is not permitted', E_USER_ERROR );
	}
	
            
    public function newObject(string $ns, string $className, string ...$params)
    {
        $class = $ns."\\".$className;
        if (method_exists($class,'getInstance')) {
            $obj = $class::getInstance();
        } else {
            $obj = new $class($params);
        }
        
        self::storeObject($obj, $className);
        return self::obj($className);
    }
    
    
	/**
	 * Armazena um objeto com uma identificação.
	 * @param unknown $object
	 * @param unknown $id
	 */
	public static function storeObject( &$object, string $id )
	{
		self::$objects[$id] = $object;
        return $object;
	}
    
    
    /**
	 * Retorna um objeto do registro
	 * @param String $id Chave do Objeto.
	 * @return object Objeto.
	 */
	public static function obj( $id )
	{   
		if (key_exists($id, self::$objects)) {
            return self::$objects[ $id ];
        } else {
            throw new \Exception("Meu Objeto $id não encontrado.");
        }
        return null;
	}
    
    /**
	 * Retorna um objeto do registro
	 * @param String $id Chave do Objeto.
	 * @return object Objeto.
	 */
	public function __get( $id )
	{   
		if (key_exists($id, self::$objects)) {
            return self::$objects[ $id ];
        } else {
            throw new \Exception("Meu Objeto $id não encontrado.");
        }
        return null;
	}
    
    /**
	 * Remove um objeto do registro
	 * @param String $id Chave do Objeto.
	 * @return boolean Remoção realizada com sucesso.
	 */
	public static function removeObject($id): bool
	{
		$ok = false;
		if ( is_object( self::$objects[ $id ] )) {
			unset(self::$objects[$id]);
			$ok = true;
		}
		return $ok;
	}
}
