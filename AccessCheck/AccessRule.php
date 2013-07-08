<?
/**
 * sets the access rules for one action
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage AccessCheck
 * @version 1.0
 */
class AccessRule{
	/**
	 * the action name
	 *
	 * @var string
	 */
	protected $actionName;

	/**
	 * if the user must be loggedin
	 *
	 * @var boolean
	 */
	protected $authNeeded;

	/**
	 * the user level required
	 *
	 * @var integer
	 */
	protected $levelNeeded;

	/**
	 * constructor
	 *
	 * @param string $actionName
	 * @param boolean $authNeeded
	 * @param integer $levelNeeded
	 */
	public function __construct($actionName = '*', $authNeeded = false, $levelNeeded = 0){
		$this
			->setActionName($actionName)
			->setAuthNeeded($authNeeded)
			->setLevelNeeded($levelNeeded);
	}

	/**
	 * setter for the action name
	 *
	 * @param string $actionName
	 * @return AccessRule
	 */
	public function setActionName($actionName){
		$this->actionName = $actionName;
		return $this;
	}

	/**
	 * getter for the action name
	 *
	 * @return string
	 */
	public function getActionName(){
		return $this->actionName;
	}

	/**
	 * setter for auth needed
	 *
	 * @param boolean $authNeeded
	 * @return AccessRule
	 */
	public function setAuthNeeded($authNeeded){
		$this->authNeeded = $authNeeded;
		return $this;
	}

	/**
	 * getter for auth needed
	 *
	 * @return boolean
	 */
	public function isAuthNeeded(){
		return $this->authNeeded;
	}

	/**
	 * setter for the needed level
	 *
	 * @param integer $levelNeeded
	 * @return AccessRule
	 */
	public function setLevelNeeded($levelNeeded){
		$this->levelNeeded = $levelNeeded;
		return $this;
	}

	/**
	 * getter for the needed level
	 *
	 * @return integer
	 */
	public function getLevelNeeded(){
		return $this->levelNeeded;
	}
}