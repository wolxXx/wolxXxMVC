<?
/**
 * Stack class = key-value-store
 * saves all data in the session
 *
 * @author wolxXx
 * @version 1.5
 * @package wolxXxMVC
 */
class Stack{
	/**
	 *
	 * array for debug messages
	 * @var array
	 */
	private $messages = array();
	/**
	 *
	 * the real stack
	 * @var array
	 */
	private $stack;

	/**
	 *
	 * instance for only having one access point
	 * @var Stack
	 */
	private static $instance = null;

	/**
	 * constructor
	 * starts a session
	 * only callable from here
	 */
	private function __construct(){
		if('' === session_id()){
			session_start();
		}
		$this->stack =& $_SESSION['wolxXxMVC'];
		if(null === $this->stack){
			$this->stack = array();
		}
	}

	/**
	 *
	 * gets the only instance
	 * @return Stack
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new Stack();
		}
		return self::$instance;
	}

	/**
	 * clears the instance
	 * @return Stack
	 */
	public static function getClearInstance(){
		session_destroy();
		self::$instance = null;
		return self::getInstance();
	}

	/**
	 *
	 * gets the value for the provided key or null if not exists
	 * @param string $key
	 * @return mixed|null
	 */
	public function get($key, $default = null){
		if(true === array_key_exists($key, $this->stack)){
			return $this->stack[$key];
		}else{
			$this->messages[] = "$key not found in stack. using default: $default";
			return $default;
		}
	}

	/**
	 *
	 * debugger
	 */
	public function debug(){
		Helper::debug($this->stack);
		return $this;
	}

	/**
	 *
	 * returns the whole stack
	 * @return array
	 */
	public function getAll(){
		return $this->stack;
	}

	/**
	 *
	 * sets a value for a key
	 * @param string $key
	 * @param mixed $value
	 * @return Stack
	 */
	public function set($key, $value){
		$this->stack[$key] = $value;
		return $this;
	}

	/**
	 * unsets a key in the stack
	 * @param string $key
	 * @return Stack
	 */
	public function unsetKey($key){
		if(true === array_key_exists($key, $this->stack)){
			unset($this->stack[$key]);
		}
		return $this;
	}

	/**
	 * clears the stack
	 * caution: will clear everything!
	 * @return Stack
	 */
	public function clear(){
		$this->stack = array();
		$this->messages = array();
		return $this;
	}

	/**
	 *
	 * returns all debug messages
	 * @return array
	 */
	public function getMessages(){
		return $this->messages;
	}
}