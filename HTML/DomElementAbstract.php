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
		$default['required'] = false;
		return $default;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::setIsRequired()
	 */
	public function setIsRequired($required = true){
		$this->set('required', true === $required);
		if(null !== $this->label){
			$this->label->addClass('required');
		}
		return $this;
	}

	/**
	 * getter for the required flag
	 *
	 * @return boolean
	 */
	public function getIsRequired(){
		return $this->get('required');
	}

	/**
	 * adds a label to the element
	 * the text param will be the visible text for the label
	 * position determines if the label shall be displayed before or after element
	 *
	 * @param string $text
	 * @param string $position
	 * @return DomElementAbstract
	 */
	public function addLabel($text = null, $position = 'before'){
		$this->label = HTML::Factory('label')
			->setText($text)
			->setFor($this->getId())
			->setPosition($position)
		;
		return $this;
	}

	/**
	 * getter for the label
	 *
	 * @return Label
	 */
	public function getLabel(){
		return $this->label;
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
		if(false === $this->data->hasKey('class')){
			$this->data->set('class', $class);
			return $this;
		}
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
	 * returns the wanted value for the key
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get($key){
		return $this->data->get($key);
	}

	/**
	 * getter for the whole data
	 *
	 * @return array
	 */
	public function getData(){
		return $this->data->getData();
	}

	/**
	 * clears the data
	 * handle with care!!
	 *
	 * @return DomElementAbstract
	 */
	public function clearData(){
		$this->data->clear();
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
	public function setId($elemId){
		$this->data->set('id', $elemId);
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
	 * (non-PHPdoc)
	 * @see DomElementInterface::getId()
	 */
	public function getId(){
		return $this->data->get('id');
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getName()
	 */
	public function getName(){
		return $this->data->get('name');
	}

	/**
	 * renders the label if one is set and the position is before
	 */
	public function displayLabelBefore(){
		if(null !== $this->label && 'before' === $this->label->getPosition()){
			$this->label->display();
		}
	}

	/**
	 * renders the label if one is set and the position is after
	 */
	public function displayLabelAfter(){
		if(null !== $this->label && 'after' === $this->label->getPosition()){
			$this->label->display();
		}
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