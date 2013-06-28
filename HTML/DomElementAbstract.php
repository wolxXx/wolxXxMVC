<?
/**
 * abstract class for havin a wrapper for dom elements
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
abstract class DomElementAbstract implements DomElementInterface{
	/**
	 * data array
	 * is used for tag properties like class, name, id, etc
	 *
	 * @var KeyValueStore
	 */
	protected $data;

	/**
	 * label for the dom element
	 *
	 * @var Label
	 */
	protected $label;

	/**
	 * returns the default config for all elements
	 *
	 * @return array
	 */
	public static function getDefaultConf(){
		$default = HTML::getUniqueIdAndName();
		$default['class'] = null;
		$default['title'] = null;
		$default['style'] = null;
		$default['placeholder'] = null;
		return $default;
	}

	/**
	 * adds a label to the element
	 * the text param will be the visible text for the label
	 *
	 * @param string $text
	 * @return DomElementAbstract
	 */
	public function addLabel($text = null){
		$this->label = HTML::Factory('label')
			->setText($text)
			->setFor($this->getId());
		return $this;
	}

	/**
	 * setter for the class
	 *
	 * @param string $class
	 * @return DomElementAbstract
	 */
	public function setClass($class){
		$this->data->set('class', $class);
		return $this;
	}

	/**
	 * adds a class to the element
	 *
	 * @param string $class
	 * @return DomElementAbstract
	 */
	public function addClass($class){
		$this->data->set('class', $this->data->get('class').' '.$class);
		return $this;
	}

	/**
	 * sets the label with an instanciated object
	 *
	 * @param Label $label
	 * @return DomElementAbstract
	 */
	public function setLabel(Label $label){
		$this->label = $label;
		return $this;
	}

	/**
	 * adds data to the data object
	 *
	 * @param array $data
	 * @return DomElementAbstract
	 */
	public function addData($data){
		$this->data->addData($data);
		return $this;
	}

	/**
	 * overwrites the whole data set
	 *
	 * @param array $data
	 * @return DomElementAbstract
	 */
	public function setData($data){
		$this->data->setData($data);
		return $this;
	}

	/**
	 * sets the key with the value in the data object
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return DomElementAbstract
	 */
	public function set($key, $value){
		$this->data->set($key, $value);
		return $this;
	}

	/**
	 * removes a key from the data object
	 *
	 * @param string $key
	 * @return DomElementAbstract
	 */
	public function removeProperty($key){
		$this->data->removeData($key);
		return $this;
	}

	/**
	 * setter for the name property
	 *
	 * @param string $name
	 * @return DomElementAbstract
	 */
	public function setName($name){
		$this->data->set('name', $name);
		return $this;
	}

	/**
	 * setter for the id of the element
	 *
	 * @param string $id
	 * @return DomElementAbstract
	 */
	public function setId($id){
		$this->data->set('id', $id);
		return $this;
	}

	/**
	 * sets the id and the name of this element
	 *
	 * @param string $nameAndId
	 * @return DomElementAbstract
	 */
	public function setNameAndId($nameAndId){
		return $this
			->setId($nameAndId)
			->setName($nameAndId);
	}

	/**
	 * getter for the id of this element
	 *
	 * @return string
	 */
	public function getId(){
		return $this->data->get('id');
	}

	/**
	 * constructor
	 *
	 * @return DomElementAbstract
	 */
	public final function __construct(){
		$this->init();
		return $this;
	}

	/**
	 * initialises the data object
	 * sets the automatic generated id and name to the element
	 *
	 * @return DomElementAbstract
	 */
	public function init(){
		$this->data = new KeyValueStore();
		$this->data->addData(HTML::getUniqueIdAndName());
		return $this;
	}
}