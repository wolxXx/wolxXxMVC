<?
/**
 * the main controller which provides main functionality
 * this cannot be instanciated - this is abstract
 * other controllers should extend this class
 * https://www.youtube.com/watch?v=vESqVS1f-Tg
 *
 * @author wolxXx
 * @version 1.1
 * @package wolxXxMVC
 */
abstract class CoreController implements CoreControllerInterface{

	/**
	 * name of the action that is running
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * the name of the view which should be displayed
	 *
	 * @var string
	 */
	public $view;

	/**
	 * files sent via post-requests
	 *
	 * @var array
	 */
	public $files = array();

	/**
	 * all files sent wrapped into object
	 *
	 * @var array
	 */
	public $fileObjects = array();

	/**
	 * if the request is an ajax-request
	 *
	 * @var boolean
	 * @deprecated
	 */
	public $isAjax = false;

	/**
	 * if the request is a post-request
	 *
	 * @var boolean
	 * @deprecated
	 */
	public $isPost = false;

	/**
	 * if the viewer is a mobile device
	 *
	 * @var boolean
	 * @deprecated
	 */
	protected $isMobile = false;

	/**
	 * an instance of the Load-class
	 *
	 * @var Load
	 */
	protected $load;

	/**
	 * an instance of the stack
	 *
	 * @var Stack
	 */
	protected $stack;

	/**
	 * where the postlog is saved
	 *
	 * @var string
	 */
	protected $postLogFile = 'postlog';

	/**
	 * an instance of the data object for accessing the GET,POST and FILES
	 *
	 * @var DataObject
	 */
	protected $dataObject;

	/**
	 * an insance of the access checker
	 *
	 * @var AccessChecker
	 */
	protected $accessChecker;

	/**
	 * information holder of the request
	 *
	 * @var Request
	 */
	protected $request;

	/**
	 * an instance of the model
	 *
	 * @var Model
	 */
	protected $model;

	/**
	 * constructor
	 *
	 * generates the data, the modes, logs post data
	 */
	public final function __construct(){
		$this->init();
	}

	/**
	 * set if after the run method should be a redirect
	 *
	 * @var Redirect
	 */
	protected $registeredRedirect = null;

	/**
	 * initialises the controller
	 */
	private final function init(){
		$this->load = Load::getInstance();
		$this->model = new Model();
		$this->stack = Stack::getInstance();
		$this->dataObject = DataObject::getInstance();
		$this->request = new Request();
		$this->initAccessChecker();
		$this->version = $this->stack->get('version');
		try{
			$this->load->setLayout($this->stack->get('version'));
		}catch(Exception $x){
			$this->load->setLayout();
		}
		$this->load->set('isAjax', $this->request->isAjax());
		$this->load->set('disable_sidebar', false);
		$this->setPostLogFile();
		$detect = new Mobile_Detect();
		if($detect->isMobile() || 'mobile' === $this->version){
			$this->isMobile = true;
			$this->load->setLayout('mobile');
			$this->version = 'mobile';
		}
	}

	/**
	 * register a redirect
	 *
	 * @param string | Redirect $url
	 * @param string $method
	 * @return CoreController
	 */
	protected function registerRedirect($url, $method = 'redirect'){
		if(true === $url instanceof Redirect){
			$this->registeredRedirect = $url;
			return $this;
		}
		$this->registeredRedirect = new Redirect($url, $method);
		return $this;
	}

	/**
	 * return the registered redirect
	 *
	 * @return Redirect | null
	 */
	public function getRegisteredRedirect(){
		return $this->registeredRedirect;
	}

	/**
	 * sets the default access rule
	 * everything is allowed!
	 * overwrite this method for your controller if needed
	 *
	 * @return CoreController
	 */
	public function setAccessRules(){
		$this->accessChecker->addRule(new AccessRule());
		return $this;
	}

	/**
	 * creates a new instance of the access checker
	 * and sets logged in and user type
	 *
	 * @return CoreController
	 */
	private final function initAccessChecker(){
		$this->accessChecker = new AccessChecker(Auth::isLoggedIn());
		if(true === Auth::isLoggedIn()){
			$this->accessChecker->setUserLevel(Auth::getUserType());
		}
		return $this;
	}

	/**
	 * check if the requested action has any restrictions
	 *
	 * @throws NotAllowedException
	 * @throws AuthRequestedException
	 */
	public final function checkAccess(){
		$authRequired = $this->accessChecker->requiresAuth($this->action);
		if(false === $authRequired){
			return;
		}
		if(false === Auth::isLoggedIn()){
			throw new AuthRequestedException();
		}
		if(false === $this->accessChecker->checkAccess($this->action)){
			throw new NotAllowedException();
		}
	}

	/**
	 * returns the currently active access checker
	 *
	 * @return AccessChecker
	 */
	public final function getAccessChecker(){
		return $this->accessChecker;
	}

	/**
	 * gets all FileUploadObjects with index = $index
	 *
	 * @param string $index
	 * @return array
	 */
	public function getFileUploadObjectsByIndex($index){
		$return = array();
		foreach($this->request->dataObject->getFiles() as $current){
			if($index === $current->uploadIndex){
				$return[] = $current;
			}
		}
		return $return;
	}

	/**
	 * getter for the current request object
	 *
	 * @return Request
	 */
	public function getRequest(){
		return $this->request;
	}

	/**
	 * getter for the wished view to be displayed
	 *
	 * @return string
	 */
	public function getView(){
		return $this->view;
	}

	/**
	 * setter for the view
	 *
	 * @param string $view
	 * @return CoreController
	 */
	protected function setView($view){
		$this->view = $view;
		return $this;
	}

	/**
	 * setter for the action
	 *
	 * @param string $action
	 * @return CoreController
	 */
	protected function setAction($action){
		$this->action = $action;
		return $this;
	}

	/**
	 * getter for the action
	 *
	 * @return string
	 */
	public function getAction(){
		return $this->action;
	}

	/**
	 * sets the name of the post log file
	 * directory will remain log/
	 *
	 * @param string $filename
	 * @return CoreController
	 */
	protected function setPostLogFile($filename = 'postlog'){
		$this->postLogFile = $filename;
		return $this;
	}

	/**
	 * getter for the file name of the post log file
	 *
	 * @return string
	 */
	public function getPostLogFile(){
		return $this->postLogFile;
	}

	/**
	 *
	 * tells the request object to log the post vars
	 * passwords will be saved as stars
	 *
	 * @return CoreController
	 */
	public function postlog(){
		$this->request->postLog($this->postLogFile);
		return $this;
	}

	/**
	 * default routing mechanism
	 *
	 * @throws NoViewException
	 * @return CoreController
	 */
	public function setActionAndView(){
		if(func_num_args() < 2 || '' === func_get_arg(1)){
			if(false === method_exists(get_called_class(), 'indexAction')){
				throw new NoViewException('no index action in controller or requested method does not exist!');
			}
			$this->view = 'index';
			$this->action = 'index';
			return $this;
		}
		if(false === method_exists($this, func_get_arg(1).'Action') && false === $this->load->viewExists(func_get_arg(1))){
			throw new NoViewException(func_get_arg(1).' is not a function and no view was found!');
		}

		$this->view = func_get_arg(1);
		if(true === method_exists($this, func_get_arg(1).'Action')){
			$this->action = func_get_arg(1);
			return $this;
		}
		$this->action = 'noop';
		return $this;
	}

	/**
	 * checks if the given args are found in the request and are not empty
	 * @param $arg_
	 * @return boolean
	 */
	protected function isRequestOk(){
		foreach(func_get_args() as $current){
			if('' === $this->dataObject->getSavely($current, '')){
				return false;
			}
		}
		return true;
	}

	/**
	 * nothing
	 * do nothing. seriously. take a seat and sit down. have a breath, a coffee. whatever you want!
	 * there is a view file which does not need a controller function. impress e.g.
	 */
	public final function noopAction(){}

	/**
	 * returns the called class
	 *
	 * @return string
	 */
	public final function __toString(){
		return get_called_class().'';
	}

	/**
	 * is always be runned before any operation was made
	 * useful for authorisation cases, measurement
	 */
	function beforeRun(){}

	/**
	 * is always runned after the run function finished and before view rendering
	 * useful for measurements
	 */
	function afterRun(){}

	/**
	 * sets all needed models
	 * just set the protected property in your class
	 * of course you can overwrite this method!!
	 */
	function setModels(){
		$reflection = new ReflectionClass($this);
		foreach($reflection->getProperties() as $current){
			$current = $current->name;
			if('Model' === substr($current, strlen($current)-5)){
				$this->$current = new $current();
			}
		}
	}

	/**
	 * this method should be implemented by all extending classes if own routing is needed
	 * usefull for routing etc
	 *
	 * @param array $args
	 */
	public final function run(){
		if(false === method_exists($this, $this->action.'Action')){
			throw new ApocalypseException($this->action.'Action is not callable in '.$this->__toString());
		}
		call_user_func_array(array($this, $this->action.'Action'), func_get_args());
	}
}