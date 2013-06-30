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
	 * constructor
	 * gets all needed singleton instances
	 */
	public function __construct(){
		$this->init();
	}

	/**
	 * getter for dynamic classes
	 * $var should be the class name
	 *
	 * @param string $var
	 * @throws Exception
	 */
	public function __get($var){
		if(true === isset($this->$var)){
			return $this->$var;
		}
		if(true === AutoLoader::isLoadable($var)){
			$this->$var = new $var;
			return $this->$var;
		}
		throw new Exception($var.' is not class member of core controller!');
	}

	/**
	 * error handling function
	 *
	 * @param integer $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param integer $errline
	 * @param string $errcontext
	 * @throws ApocalypseException
	 */
	public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext){
		Load::getInstance()->clearBuffer();
		Helper::dieDebug(func_get_args());
		throw new ApocalypseException('wtf?!');
	}

	/**
	 * catches the exception that is thrown in error handler
	 */
	public static function catchException(){
		Load::getInstance()->clearBuffer();
		if(true === Helper::isDebugEnabled()){
			Helper::dieDebug(func_get_args());
		}
		throw new Exception('oO');
	}

	/**
	 * initing everything usefull
	 */
	private final function init(){
		set_exception_handler(array('CoreBootstrap', 'catchException'));
		set_error_handler(
			function ($code, $message, $file, $line) {
				if(true === Helper::isDebugEnabled()){
					Helper::dieDebug(func_get_args());
				}
				throw new Exception();
			}, -1
		);
		if(true === file_exists('application/config/defines.php')){
			require_once 'application/config/defines.php';
		}
		$logPath = 'log/phperror.log';

		if(true === defined('LOGPATH')){
			$logPath = LOGPATH;
		}
		ini_set('error_log', $logPath);

		if('' === session_id()){
			session_start();
		}

		$this->stack = Stack::getInstance();


		/**
		 * application_env is always pattern version-modus
		 * 		version 	€{main, mobile, facebook}
		 * 	 	mode 		€{production, dev}
		*/
		if(false === getenv('APPLICATION_ENV')){
			putenv('APPLICATION_ENV=main-production');
		}

		$split = explode('-', getenv('APPLICATION_ENV'));
		$version = $split[0];
		$mode = $split[1];
		$this->stack->set('version', $version);
		$this->stack->set('mode', $mode);

		/**
		 *
		 * grab the host name
		*/
		$hostname = isset($hostname)? $hostname : php_uname("n");
		$this->stack->set('hostname', $hostname);

		/**
		 * now, get the config
		*/
		if(false === AutoLoader::isLoadable('HostConfig')){
			throw new ApocalypseException('you need to create a HostConfig object in /application/config');
		}
		$config = new HostConfig();
		$config->configureApplication();
		$config->configureHost();
		$config->checkConfig();
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
		if(null !== $this->controller->getRegisteredRedirect()){
			$this->controller->getRegisteredRedirect()->redirect();
		}
		call_user_func_array(array($this->controller, "setAccessRules"), $this->path);
		call_user_func_array(array($this->controller, "checkAccess"), $this->path);
		call_user_func_array(array($this->controller, "beforeRun"), $this->path);
		if(true === $this->controller->getRequest()->isPost()){
			$this->controller->postlog();
		}
		if(null !== $this->controller->getRegisteredRedirect()){
			$this->controller->getRegisteredRedirect()->redirect();
		}
		call_user_func_array(array($this->controller, "run"), $this->path);
		if(null !== $this->controller->getRegisteredRedirect()){
			$this->controller->getRegisteredRedirect()->redirect();
		}
		call_user_func_array(array($this->controller, "afterRun"), $this->path);
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
		$this->request = $_SERVER['REQUEST_URI'];
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