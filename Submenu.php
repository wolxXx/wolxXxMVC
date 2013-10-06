<?
/**
 * the submenu item container
 *
 * @author wolxXx
 * @package application
 * @version 1.0
 */
class Submenu{
	/**
	 * the one and only instance
	 *
	 * @var Submenu
	 */
	protected static $instance = null;

	/**
	 * the submenu item containing array
	 *
	 * @var array
	 */
	protected $items = array();

	/**
	 * getter for the instance
	 *
	 * @return Submenu
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * private constructor in sense of singleton pattern
	 */
	private function __construct(){
		$this->items = array();
	}

	/**
	 * adds a submenuitem to the container
	 *
	 * @param SubmenuItem $item
	 * @return Submenu
	 */
	public function addItem(SubmenuItem $item){
		$this->items[] = $item;
		return $this;
	}

	/**
	 * returns all set submenu items
	 *
	 * @return array
	 */
	public function getAllItems(){
		return $this->items;
	}

	/**
	 * renders the submenu if it contains some elements
	 */
	public function display(){
		if(true === empty($this->items)){
			return;
		}
		echo '<div id="submenu">';
		foreach($this->items as $current){
			$current->display();
		}
		echo '</div>';
	}
}