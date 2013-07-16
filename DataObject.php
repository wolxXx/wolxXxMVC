<?
/**
 * provides all sent data as object access
 *
 * @author wolxXx
 * @version 1.2
 * @package wolxXxMVC
 */
class DataObject{
	/**
	 * instance for wanted singleton pattern
	 *
	 * @var DataObject
	 */
	protected static $instance;

	/**
	 * the main data storage
	 *
	 * @var array
	 */
	private $data;

	/**
	 * storage for file-array
	 *
	 * @var array
	 */
	private $files;

	/**
	 * set of FileUploadObjects
	 *
	 * @var array
	 */
	private $fileObjects;

	/**
	 * a save of the original GET-array
	 *
	 * @var array
	 */
	private $rawGET;

	/**
	 * a save of the original POST-array
	 *
	 * @var array
	 */
	private $rawPOST;

	/**
	 * a save of the original FILES-array
	 *
	 * @var array
	 */
	private $rawFILES;

	/**
	 * instanciates all arrays, starts scanning the data
	 *
	 * @return DataObject
	 */
	public function __construct(){
		$this->init();
	}

	/**
	 * singleton instance getter
	 *
	 * @return DataObject
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * initialises the data object
	 *
	 * @return DataObject
	 */
	protected function init(){
		$this->data = array();
		$this->files = array();
		$this->fileObjects = array();
		$this->rawPOST = $_POST;
		$this->rawGET = $_GET;
		$this->rawFILES = $_FILES;
		$this->scanGetData();
		$this->scanPostData();
		$this->scanFiles();
		return $this;
	}

	/**
	 * scans the post array and saves the data
	 *
	 * @return DataObject
	 */
	private function scanPostData(){
		foreach($this->rawPOST as $key => $value){
			$this->data[$key] = $value;
		}
		return $this;
	}

	/**
	 * scans the get array and saves the data
	 *
	 * @return DataObject
	 */
	private function scanGetData(){
		foreach($this->rawGET as $key => $value){
			$this->data[$key] = $value;
		}
		return $this;
	}

	/**
	 * scans the files and saves all files as new FileUploadObjects
	 *
	 * @return DataObject
	 */
	private function scanFiles(){
		foreach($this->rawFILES as $key => $value){
			$this->files[$key] = $value;
			if(is_array($value['name'])){
				foreach(array_keys($value['name']) as $index){
					$this->fileObjects[] = new FileUploadObject($value['name'][$index], $value['type'][$index], $value['tmp_name'][$index], $value['error'][$index], $value['size'][$index], $key);
				}
			}else{
				$this->fileObjects[] = new FileUploadObject($value['name'], $value['type'], $value['tmp_name'], $value['error'], $value['size'], $key);
			}
		}
		return $this;
	}

	/**
	 * returns all FileUploadObjects
	 *
	 * @return FileUploadObject[]
	 */
	public function getFiles(){
		return $this->fileObjects;
	}

	/**
	 * returns the whole original POST data
	 *
	 * @return array
	 */
	public function getRawPOST(){
		return $this->rawPOST;
	}

	/**
	 * returns the whole original GET data
	 *
	 * @return array
	 */
	public function getRawGET(){
		return $this->rawGET;
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
	 * returns data for the key
	 * post data has higher priority than get data
	 *
	 * @param string $key
	 * @throws KeyNotExistsInDataObject
	 * @return mixed
	 */
	public function get($key){
		try{
			return $this->getFromPost($key);
		}catch (KeyNotExistsInDataObject $x){

		}
		try{
			return $this->getFromGet($key);
		}catch (KeyNotExistsInDataObject $x){

		}
		throw new KeyNotExistsInDataObject($key.' not found in DataObject!');
	}

	/**
	 * tries to recieve the value for the key
	 * returns null if nothing was found
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed | null
	 */
	public function getSavely($key, $default = null){
		try{
			return $this->get($key);
		}catch (KeyNotExistsInDataObject $x){
			return $default;
		}
	}

	/**
	 * tries to retrieve a value directly from the post array by its key
	 *
	 * @param string $key
	 * @throws KeyNotExistsInDataObject
	 * @return mixed
	 */
	public function getFromPost($key){
		if(true === isset($this->rawPOST[$key])){
			return $this->rawPOST[$key];
		}
		throw new KeyNotExistsInDataObject($key.' not found in DataObject in POST-Section!');
	}

	/**
	 * tries to retrieve a value directly from the get array by its key
	 *
	 * @param string $key
	 * @throws KeyNotExistsInDataObject
	 * @return mixed
	 */
	public function getFromGet($key){
		if(true === isset($this->rawGET[$key])){
			return $this->rawGET[$key];
		}
		throw new KeyNotExistsInDataObject($key.' not found in DataObject in GET-Section!');
	}


	/**
	 * tries to get data via direct object access
	 *
	 * @param string $key
	 * @throws KeyNotExistsInDataObject
	 * @return mixed
	 */
	public function __get($key){
		return $this->get($key);
	}

	/**
	 * checks, if data was set for $key
	 *
	 * @param boolean $key
	 */
	public function hasDataForKey($key){
		return isset($this->data[$key]);
	}

	/**
	 * removes the key from post, get and internal data arrays
	 *
	 * @param string $key
	 * @return DataObject
	 */
	public function removeKey($key){
		unset($this->rawGET[$key]);
		unset($this->rawPOST[$key]);
		unset($this->data[$key]);
		return $this;
	}

	/**
	 * debug all set data
	 *
	 * @return DataObject
	 */
	public function debug(){
		echo "<pre>POST</pre>";
		Helper::debug($this->getRawPOST());

		echo "<pre>GET</pre>";
		Helper::debug($this->getRawGET());

		echo "<pre>FILES</pre>";
		Helper::debug($this->getFiles());

		return $this;
	}
}