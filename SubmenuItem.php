<?
/**
 * item of the submenu
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @version 1.0
 */
class SubmenuItem{
	/**
	 * container for metadata
	 *
	 * @var array
	 */
	protected $data = array();

	/**
	 * constructor
	 * config array can contain meta data
	 *
	 * @param array $config
	 */
	public function __construct($config = array()){
		$this->data = array_merge(self::getDefaultArray(), $config);
	}

	/**
	 * factory for havin simple access to new item
	 *
	 * @param string $href
	 * @param string $text
	 * @param array $config
	 * @return SubmenuItem
	 */
	public static function Factory($href, $text, $config = array()){
		return new self(array_merge(array('href' => $href, 'text' => $text), $config));
	}

	/**
	 * setter for meta data
	 *
	 * @param string $key
	 * @param string $value
	 */
	public function set($key, $value){
		$this->data[$key] = $value;
	}

	/**
	 * generates the link string
	 *
	 * @return string
	 */
	public function getOutput(){
		return sprintf('<a href="%s" class="%s" id="%s">%s</a>', $this->data['href'], $this->data['class'], $this->data['id'], $this->data['text']);
	}

	/**
	 * echoes the generated link string
	 */
	public function display(){
		echo $this->getOutput();
	}

	/**
	 * generates the default config
	 *
	 * @return array
	 */
	public static function getDefaultArray(){
		return $defaultData = array(
			'href' => '#',
			'class' => '',
			'id' => ''
		);
	}
}