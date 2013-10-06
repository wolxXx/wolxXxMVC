<?
/**
 * basic basement for mvc
 * runs some before and after functions
 * initialises analysing of url
 * instanciates the controller
 * calls view from loader
 *
 * @author wolxXx
 * @version 1.1
 * @package wolxXxMVC
 *
 */
abstract class CoreBootstrap{
	/**
	 * the cleared query string
	 *
	 * @var string
	 */
	public $request;

	/**
	 * contains the splitted url blocks of the $request
	 *
	 * @var array
	 */
	public $path;

	/**
	 * instance of Load
	 *
	 * @var Load
	 */
	public $load;

	/**
	 * instance of Model
	 *
	 * @var Model
	 */
	public $model;

	/**
	 * instance of Stack
	 *
	 * @var Stack
	 */
	public $stack;

	/**
	 * instanciated object of a controller
	 *
	 * @var CoreController
	 */
	public $controller;

	/**
	 * instance of the router
	 *
	 * @var Router
	 */
	public $router;

	/**
	 * constructor
	 * gets all needed singleton instances
	 */
	public final function __construct(){
		$this->init();
	}

	/**
	 * get the config
	 */
	protected final function config(){
		if(false === AutoLoader::isLoadable('HostConfig')){
			throw new ApocalypseException('you need to create a HostConfig object in /application/config');
		}
		$config = new HostConfig();
		$config->configureApplication();
		$config->configureHost();
		$config->checkConfig();
	}

	/**
	 * initing everything usefull
	 */
	private final function init(){
		if(false === isset($_SERVER['argv'])){
			set_exception_handler(function(){
				Helper::dieDebug(func_get_args());
			});
			set_error_handler(function(){
				call_user_func_array(array('wolxXxMVC', 'errorHandler'), func_get_args());
			}, -1);
		}
		if(true === file_exists('application/config/defines.php')){
			require_once 'application/config/defines.php';
		}
		$logPath = 'log/phperror.log';

		if(true === defined('LOGPATH')){
			$logPath = LOGPATH;
		}
		ini_set('error_log', $logPath);
		if(false === defined('STDIN') && '' === session_id()){
			session_start();
		}

		$this->router = new Router();
		Helper::grabModeAndVersion();
		Helper::grabHostName();

		$this->stack = Stack::getInstance();
		$this->config();

		$this->model = new Model();
		$this->load = Load::getInstance();
	}

	/**
	 * called by mvc before the main run function
	 * can be overwriten by user's wanted own bootstrap class
	 * default behaviour: do the wolxXx style. do nothing :)
	 */
	public function beforeRun(){}

	/**
	 * called by mvc after the main run function
	 * can be overwriten by user's wanted own bootstrap class
	 * default behaviour: do the wolxXx style. do nothing :)
	 */
	public function afterRun(){}

	/**
	 * called by mvc before the main view function
	 * can be overwriten by user's wanted own bootstrap class
	 * default behaviour: do the wolxXx style. do nothing :)
	 */
	public function beforeView(){}

	/**
	 * called by mvc after  the main run function
	 * can be overwriten by user's wanted own bootstrap class
	 * default behaviour: do the wolxXx style. do nothing :)
	 */
	public function afterView(){}

	/**
	 * calls analyze
	 * instanciates controller
	 * calls controller's before run action
	 * calls controller's run action
	 * calls controller's after run action
	 */
	public final function run(){
		$this->analyzeRequest();
		$controller = $this->getController();
		$this->controller = new $controller();
		$this->stack->set('controller', strtolower(str_replace('Controller', '', $this->controller->__toString())));
		call_user_func_array(array($this->controller, "setModels"), $this->path);
		call_user_func_array(array($this->controller, "setActionAndView"), $this->path);
		$this->checkRegisteredRedirect();
		call_user_func_array(array($this->controller, "setAccessRules"), $this->path);
		call_user_func_array(array($this->controller, "checkAccess"), $this->path);
		call_user_func_array(array($this->controller, "beforeRun"), $this->path);
		if(true === $this->controller->getRequest()->isPost()){
			$this->controller->postlog();
		}
		$this->checkRegisteredRedirect();
		call_user_func_array(array($this->controller, "run"), $this->path);
		$this->checkRegisteredRedirect();
		call_user_func_array(array($this->controller, "afterRun"), $this->path);
		$this->checkRegisteredRedirect();
	}

	/**
	 * checks if the controller registered a redirect
	 */
	protected function checkRegisteredRedirect(){
		if(null !== $this->controller->getRegisteredRedirect()){
			$this->controller->getRegisteredRedirect()->redirect();
		}
	}


	/**
	 * calls the loader's view function
	 */
	public function view(){
		$this->load->view($this->controller->getView());
	}

	/**
	 * splitts the request_uri from http request
	 * clears all get params
	 * trims all tailing slashes
	 */
	public function analyzeRequest(){
		$this->request = Helper::getCurrentURI();
		$this->path = explode('?', $this->request, 2);
		$this->request = trim($this->path[0], '/');
		$this->path = explode('/', $this->request);
	}

	/**
	 * determines if a file exists that contains a controller class
	 * if no match is found, the cms controller is returned
	 *
	 * @return string
	 */
	public function getController(){
		if(file_exists('application/controllers/'.ucfirst($this->path[0]).'Controller.php')){
			$controller = ucfirst($this->path[0]).'Controller';
		}else{
			$controller = 'CmsController';
		}
		return $controller;
	}
}