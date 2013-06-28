<?
/**
 * a simple key value data storage
 *
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 *
 */
class KeyValueStore{

	/**
	 * the stored data
	 *
	 * @var array
	 */
	protected $data;

	/**
	 * constructor
	 *
	 * @param array $data
	 */
	public function __construct($data = array()){
		$this->setData($data);
	}

	/**
	 * setter for a single key value pair
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return KeyValueStore
	 */
	public function set($key, $value){
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * getter for a single key value pair
	 *
	 * @param string $key
	 * @throws Exception if key not found
	 * @return mixed
	 */
	public function get($key){
		if(false === array_key_exists($key, $this->data)){
			throw new Exception('key "'.$key.'" not found in data');
		}
		return $this->data[$key];
	}

	/**
	 * overwrites the whole current data array
	 *
	 * @param array $data
	 * @return KeyValueStore
	 */
	public function setData($data){
		$this->data = $data;
		return $this;
	}

	/**
	 * adds data to the current data array
	 * if overwrite is set to true, it overwrite existing data keys
	 *
	 * @param array $data
	 * @param boolean $overwrite
	 * @return KeyValueStore
	 */
	public function addData($data, $overwrite = true){
		$array1 = $data;
		$array2 = $this->data;
		if(true === $overwrite){
			$array1 = $this->data;
			$array2 = $data;
		}
		$this->data = array_merge($array1, $array2);
		return $this;
	}

	/**
	 * returns the whole data array
	 *
	 * @return array
	 */
	public function getData(){
		return $this->data;
	}

	/**
	 * removes a key value pair
	 *
	 * @param string $key
	 * @return KeyValueStore
	 */
	public function removeData($key){
		unset($this->data[$key]);
		return $this;
	}

	/**
	 * clears the whole store
	 *
	 * @return KeyValueStore
	 */
	public function clear(){
		$this->data = array();
		return $this;
	}
}