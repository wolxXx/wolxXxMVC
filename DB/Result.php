<?
/**
 * the plain object item
 * simple databaes row to object wrapper
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.3
 */
class Result{
	/**
	 * global setter for object properties
	 *
	 * @param string $key
	 * @param any $value
	 */
	public function __set($key, $value){
		$this->$key = $value;
	}

	/**
	 * getter for a key
	 *
	 * @param string $key
	 * @return any
	 */
	public function get($key){
		return $this->$key;
	}

	/**
	 * global getter for object properties
	 *
	 * @param string $key
	 * @return any
	 */
	public function __get($key){
		if(false === property_exists($this, $key)){
			$properties =  implode(', ', array_keys(get_object_vars($this)));
			Helper::logerror('warning: '.$key.' not found. only got '.$properties);
			return null;
		}
		return $this->$key;
	}

	/**
	 * constructor
	 * needed for serialization
	 */
	public function __construct(){
	}

	/**
	 * needed for serialization
	 */
	public function __wakeup(){
		$this->__construct();
	}

	/**
	 * needed for serialization
	 *
	 * @return array
	 */
	public function __sleep(){
		return array_keys(get_object_vars($this));
	}
}