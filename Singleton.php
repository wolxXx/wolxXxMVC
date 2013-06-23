<?
/**
 * reuseable singleton pattern implementing class
 * extending classes should provide a createInstance method for not returning instanicated class
 *  
 * @author wolxXx
 * @version 0.1
 * @package wolxXxMVC
 */
abstract class Singleton{
	/**
	 * list of instances
	 * 
	 * @var array
	 */
	protected static $instances = array();
	
	/**
	 * creates a new instance if it does not exist
	 * returns that instance
	 * 
	 * @return Singleton
	 */
	public static function getInstance(){
		if(false === isset(static::$instances[get_called_class()])){
			if(true === method_exists(get_called_class(), 'createInstance')){
				static::$instances[get_called_class()] = static::createInstance(); 
			}else{
				static::$instances[get_called_class()] = new static();  
			}
		}
		
		return self::$instances[get_called_class()];
		
		if(null === static::$instance){
			if(true === method_exists(get_called_class(), 'createInstance')){
				static::$instance = static::createInstance();
			}else{
				$className = get_called_class();
				static::$instance = new static();
			}
		}
		return static::$instance;
	}

	protected function __construct(){
		
	}
}