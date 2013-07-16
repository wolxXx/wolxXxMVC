<?
/**
 * checks the right to access actions in the controller
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage AccessCheck
 * @version 1.0
 */
class AccessChecker{
	/**
	 * is the current user loggedin?
	 *
	 * @var boolean
	 */
	protected $userIsLoggedIn;

	/**
	 * which level has the current user?
	 *
	 * @var integer
	 */
	protected $userLevel;

	/**
	 * set of AccessRules
	 *
	 * @var array
	 */
	protected $rules;

	/**
	 * constructor
	 *
	 * @param boolean $userIsLoggedIn
	 * @param integer $userLevel
	 */
	public function __construct($userIsLoggedIn = false, $userLevel = 0){
		$this->rules = array();
		$this->userIsLoggedIn = $userIsLoggedIn;
		$this->userLevel = $userLevel;
	}

	/**
	 * setter for user is logged in
	 *
	 * @param boolean $userIsLoggedIn
	 * @return AccessChecker
	 */
	public function setUserIsLoggedIn($userIsLoggedIn){
		$this->userIsLoggedIn = $userIsLoggedIn;
		return $this;
	}

	/**
	 * checks if the user is logged in
	 *
	 * @return boolean
	 */
	public function isUserLoggedIn(){
		return $this->userIsLoggedIn;
	}

	/**
	 * setter for the user level
	 *
	 * @param integer $userLevel
	 * @return AccessChecker
	 */
	public function setUserLevel($userLevel){
		$this->userLevel = $userLevel;
		return $this;
	}

	/**
	 * getter for the user user
	 * @return integer
	 */
	public function getUserLevel(){
		return $this->userLevel;
	}

	/**
	 * adds a rule to the ruleset
	 *
	 * @param AccessRule $rule
	 * @return AccessChecker
	 */
	public function addRule(AccessRule $rule){
		$this->rules[$rule->getActionName()] = $rule;
		return $this;
	}

	/**
	 * returns all set rules
	 *
	 * @return array
	 */
	public function getRules(){
		return $this->rules;
	}

	/**
	 * removes a rule from the ruleset
	 *
	 * @param string $actionName
	 * @return AccessChecker
	 */
	public function removeRule($actionName){
		if(array_key_exists($actionName, $this->rules)){
			unset($this->rules[$actionName]);
		}
		return $this;
	}

	/**
	 * clears all set rules
	 *
	 * @return AccessChecker
	 */
	public function clearRules(){
		$this->rules = array();
		return $this;
	}

	/**
	 * checks the possibility to access the requested action
	 * if no rule was found it returns true
	 * otherwise return the result of the checked rule
	 *
	 * @param string $actionName
	 * @return boolean
	 * @throws ApocalypseException
	 */
	public function checkAccess($actionName){
		$access = true;
		$rule = null;
		if(true === array_key_exists($actionName, $this->rules)){
			/*
			 * there exists a rule for the action name
			 */
			$rule = $this->rules[$actionName];
		}elseif(true === array_key_exists('*', $this->rules)){
			/*
			 * a wildcard exists
			 */
			$rule = $this->rules['*'];
		}
		if(null !== $rule){
			/*
			 * a rule was found, check it!
			 */
			$access = $this->checkAccessRule($rule);
		}else{
			throw new ApocalypseException();
		}
		return $access;
	}

	/**
	 * checks if a rule was added for an explicite action
	 *
	 * @param string $actionName
	 * @return boolean
	 */
	public function hasRuleForAction($actionName){
		return array_key_exists($actionName, $this->rules);
	}

	/**
	 * checks if the requested action requires a logged in user
	 *
	 * @param string $actionName
	 * @return boolean
	 */
	public function requiresAuth($actionName){
		if(array_key_exists($actionName, $this->rules)){
			return $this->rules[$actionName]->isAuthNeeded();
		}
		if(array_key_exists('*', $this->rules)){
			return $this->rules['*']->isAuthNeeded();
		}
		return false;
	}

	/**
	 * checks if the current user is allowed to run the requested action
	 *
	 * @param AccessRule $rule
	 * @return boolean
	 */
	protected function checkAccessRule(AccessRule $rule){
		if(false === $rule->isAuthNeeded()){
			return true;
		}
		if(false === $this->userIsLoggedIn){
			return false;
		}
		if($rule->getLevelNeeded() <= $this->userLevel){
			return true;
		}
		return false;
	}
}