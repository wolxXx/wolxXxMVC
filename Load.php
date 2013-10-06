<?
/**
 * the loader / the bridge to the view
 * extracts the setted vars to the view
 * handles partials
 * holds JavaScript, JavaScriptFiles, CSS and CSSFiles
 *
 * @author wolxXx
 * @version 1.6
 * @package wolxXxMVC
 */

class Load{
	/**
	 * params and variables to the views
	 *
	 * @var array
	 */
	private $params = array();

	/**
	 * an instance of the stack
	 *
	 * @var Stack
	*/
	private $stack;

	/**
	 * the name of the layout
	 *
	 * @var string
	 */
	private $layout = 'main';

	/**
	 * the default layout
	 *
	 * @var string
	 */
	private $defaultLayout = 'main';

	/**
	 * additional javascripts for the view
	 * usefull for pushing javascripts to the html's head-section
	 * makes cleaner html output
	 *
	 * @var array
	 */
	private $javascriptFiles = array();

	/**
	 * additional css for the view
	 * usefull for pushing css to the html's head-section
	 * makes cleaner html output
	 *
	 * @var array
	*/
	private $cssFiles = array();

	/**
	 * just text that is filled with javascript code
	 *
	 * @var string
	*/
	private $javascript = '';

	/**
	 * just text that is filled with css code
	 *
	 * @var string
	 */
	private $css = '';

	/**
	 * singleton pattern instance
	 *
	 * @var Load
	 */
	private static $instance;

	/**
	 * the path to the layouts
	 *
	 * @var string
	 */
	protected $layoutPath = '';

	/**
	 * the path to the views
	 *
	 * @var string
	 */
	protected $viewPath = '';

	/**
	 * constructor is private because of singleton access
	 */
	private final function __construct(){
		$this->params = array();
		$this->stack = Stack::getInstance();
		$this->setLayoutPath($this->stack->get('layoutPath', Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR.'layout'.DIRECTORY_SEPARATOR.'layouts'));
		$this->setLayout($this->layout);
		$this->setViewPath($this->stack->get('viewPath', Helper::getDocRoot().'views'.DIRECTORY_SEPARATOR));
	}

	/**
	 * setter for the view path
	 *
	 * @param string $path
	 * @return Load
	 */
	public function setViewPath($path){
		$this->viewPath = Helper::addTailingSlashIfNeeded($path);
		return $this;
	}

	/**
	 * getter for the view path
	 *
	 * @return string
	 */
	public function getViewPath(){
		return $this->viewPath;
	}

	/**
	 * setter for the layout path
	 *
	 * @param string $path
	 * @return Load
	 */
	public function setLayoutPath($path){
		$this->layoutPath = Helper::addTailingSlashIfNeeded($path);
		return $this;
	}

	/**
	 * getter for the layout path
	 * @return string
	 */
	public function getLayoutPath(){
		return $this->layoutPath;
	}

	/**
	 * singleton pattern instance getter
	 *
	 * @return Load
	 */
	public static function getInstance(){
		if(null === self::$instance){
			self::$instance = new Load();
		}
		return self::$instance;
	}

	/**
	 * clear the instance
	 *
	 * @return Load
	 */
	public static function clearInstance(){
		self::getInstance()->clearBuffer();
		self::$instance = null;
		return self::getInstance();
	}

	/**
	 * sets a variable
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return Load
	 */
	function set($key, $value){
		$this->params[$key] = $value;
		return $this;
	}

	/**
	 * gets a variable or null if not defined
	 *
	 * @param string $key
	 * @return mixed|null
	 */
	function get($key){
		if(array_key_exists($key, $this->params)){
			return $this->params[$key];
		}
		return null;
	}

	/**
	 * debugs current layout, params, js, js files, css, css files
	 *
	 * @return Load
	 *
	 */
	public function debug(){
		Helper::debug(
			'current layout',
			$this->layout,
			'current layout path',
			$this->layoutPath,
			'current view path',
			$this->viewPath,
			'params',
			$this->params,
			'javascript text',
			$this->javascript,
			'javascript files',
			$this->javascriptFiles,
			'css text',
			$this->css,
			'css files',
			$this->cssFiles
		);
		return $this;
	}

	/**
	 * sets a layout
	 *
	 * @param string $name
	 * @return Load
	 */
	function setLayout($name = 'main'){
		$this->layout = $name;
		if(false === file_exists($this->layoutPath.$this->layout.'.php')){
			throw new Exception('layout "'.$name.'" not found!');
		}
		return $this;
	}

	/**
	 * gets the name of the set layout
	 *
	 * @return string
	 */
	function getLayout(){
		return $this->layout;
	}

	/**
	 * determinates if a view file with $name exists
	 *
	 * @param string $name
	 * @return boolean
	 */
	function viewExists($name){
		return null !== $this->getView($name);
	}

	/**
	 * retrieves the path of a file
	 * if a prefix is set (layout) it checks if there exists a special view
	 * if not the default view is returned if one exists
	 * if not null is returned
	 *
	 * @param string $file_name
	 * @return string|null
	 */
	function getView($file_name){
		if($this->layout !== $this->defaultLayout){
			$prefix = Helper::addTailingSlashIfNeeded($this->layout);
		}else{
			$prefix = '';
		}
		$possibleMatches = array(
			#'views/mobile/api/foo'
			$this->viewPath.$prefix.$this->stack->get('controller').DIRECTORY_SEPARATOR.$file_name,

			#'/views/mobile/foo'
			$this->viewPath.$prefix.$file_name,

			#'/views/api/foo'
			$this->viewPath.$this->stack->get('controller').DIRECTORY_SEPARATOR.$file_name,

			#'/views/foo'
			$this->viewPath.$file_name
		);
		foreach($possibleMatches as $current){
			$current .= '.php';
			if(true === is_file($current)){
				return $current;
			}
		}
		return null;
	}

	/**
	 * runs the view, buffers it, and then calls the layout
	 *
	 * @param string $file_name
	 * @throws NoViewException
	 * @return Load
	 */
	function view($file_name = null){
		$file = $this->getView($file_name);
		if(null === $file || null === $file_name){
			throw new NoViewException();
		}
		extract($this->params);
		ob_start();
		require_once $file;
		$this->params['content'] = ob_get_clean();
		extract($this->params);
		if(true === $this->get('isAjax')){
			echo $this->params['content'];
			return $this;
		}
		if(false === file_exists($this->layoutPath.$this->layout.'.php')){
			Helper::logerror('layout "'.$this->layout.'" not found, displaying default layout.');
			$this->layout = $this->defaultLayout;
		}
		require_once($this->layoutPath.$this->layout.'.php');
		return $this;
	}

	/**
	 * tries to render a partial view
	 * if $passtrough is set, data contains the datas
	 *
	 * @param string $name
	 * @param mixed $datas
	 * @param boolean $passtrough
	 * @return Load
	 */
	function partial($name, $datas = null, $passtrough = false){
		$file = $this->getView($name);
		if(null === $file){
			Helper::logerror(sprintf('partial "%s" not found!', $name));
			return $this;
		}
		if(true === is_array($datas) && false === $passtrough){
			foreach($datas as $key => $value){
				$$key = $value;
			}
		}else{
			$this->params['data'] = $datas;
		}
		extract($this->params);
		include $file;
		return $this;
	}

	/**
	 * puts a name of a js-file into the array
	 * if $top is set to true, it puts it to the top of the array so it will be displayed first
	 * be aware that there is no guarantee, that the provided item is really the first because this operation
	 * can be performed multiple times
	 * useful for loading dependencies
	 *
	 * @param string $name
	 * @param boolean $top
	 * @return Load
	 */
	public function addJavascriptFile($name, $top = false){
		$name .= '.js' !== substr($name, strlen($name) - 3, strlen($name))? '.js' : '';
		if(true === $top){
			$this->javascriptFiles = array_merge(array($name), $this->javascriptFiles);
			return $this;
		}

		$this->javascriptFiles[] = $name;
		return $this;
	}

	/**
	 * adds multiple javascript files to the array
	 *
	 * @param array $files
	 * @return Load
	 */
	public function addJavascriptFiles($files = array()){
		foreach($files as $file){
			$this->addJavascriptFile($file);
		}
		return $this;
	}

	/**
	 * returns the array with the file names
	 *
	 * @return array
	 */
	public function getJavascriptFiles(){
		return $this->javascriptFiles;
	}

	/**
	 * gets the plain javascript
	 *
	 * @return string
	 */
	public function getJavascript(){
		return $this->javascript;
	}

	/**
	 * adds text to the javascript text
	 *
	 * @param string $text
	 * @param boolean $top
	 * @return Load
	 */
	public function addJavascript($text, $top = false){
		if(true === $top){
			$this->javascript = $text.PHP_EOL.$this->javascript;
			return $this;
		}
		$this->javascript = $this->javascript.PHP_EOL.$text;
		return $this;
	}

	/**
	 * puts a name of a css-file into the array
	 * if $top is set to true, it puts it to the top of the array so it will be displayed first
	 * be aware that there is no guarantee, that the provided item is really the first because this operation
	 * can be performed multiple times
	 * useful for loading dependencies
	 *
	 * @param string $name
	 * @param boolean $top
	 * @return Load
	 */
	public function addCssFile($name, $top = false){
		$name .= '.css' !== substr($name, strlen($name) - 4, strlen($name))? '.css' : '';
		if(true === $top){
			$this->cssFiles = array_merge(array($name), $this->cssFiles);
		}else{
			$this->cssFiles = array_merge($this->cssFiles, array($name));
		}
		$this->cssFiles = array_unique($this->cssFiles);
		return $this;
	}

	/**
	 * adds multiple css files to the array
	 *
	 * @param array $files
	 * @return Load
	 */
	public function addCssFiles($files = array()){
		foreach($files as $file){
			$this->addJavascriptFile($file);
		}
		return $this;
	}

	/**
	 * returns the array with the file names
	 *
	 * @return array
	 */
	public function getCssFiles(){
		return $this->cssFiles;
	}

	/**
	 * returns the css
	 *
	 * @return string
	 */
	public function getCss(){
		return $this->css;
	}

	/**
	 * adds text to the css text
	 *
	 * @param string $text
	 * @param boolean $top
	 * @return Load
	 */
	public function addCss($text, $top = false){
		if(true === $top){
			$this->css = $text.PHP_EOL.$this->css;
		}else{
			$this->css = $this->css.PHP_EOL.$text;
		}
		return $this;
	}

	/**
	 * returns all set css files as one string
	 *
	 * @return string
	 */
	public function getMergedCss(){
		return $this->requireToBuffer($this->getCssFiles()).PHP_EOL.$this->css;
	}

	/**
	 * returns all set javascript files as one string
	 *
	 * @return string
	 */
	public function getMergedJavascript(){
		return $this->requireToBuffer($this->getJavascriptFiles()).PHP_EOL.$this->javascript;
	}

	/**
	 * requires all set js or css files into output buffer
	 * so no extra files will be loaded
	 * maybe saves a few bytes and some additional requests
	 *
	 * @param array $files
	 * @return string
	 */
	private function requireToBuffer($files){
		ob_start();
		foreach($files as $current){
			echo file_get_contents(ltrim($current, '/'));
		}
		return ob_get_clean();
	}

	/**
	 * clears the buffer and moves it to /dev/null
	 *
	 * @return Load
	 */
	public function clearBuffer(){
		while(ob_get_level() > 1){
			ob_get_clean();
		}
		return $this;
	}

	/**
	 * clears all set javascript strings
	 *
	 * @return Load
	 */
	public function clearJavascript(){
		$this->javascript = '';
		return $this;
	}

	/**
	 * clears all set javascript files
	 *
	 * @return Load
	 */
	public function clearJavascriptFiles(){
		$this->javascriptFiles = array();
		return $this;
	}

	/**
	 * clears all set javascript files and  strings
	 *
	 * @return Load
	 */
	public function clearAllJavascript(){
		$this->clearJavascript();
		$this->clearJavascriptFiles();
		return $this;
	}

	/**
	 * clears all set css strings
	 *
	 * @return Load
	 */
	public function clearCss(){
		$this->css = '';
		return $this;
	}

	/**
	 * clears all set css files
	 *
	 * @return Load
	 */
	public function clearCssFiles(){
		$this->cssFiles = array();
		return $this;
	}

	/**
	 * clears all set javascript files and  strings
	 *
	 * @return Load
	 */
	public function clearAllCss(){
		$this->clearCss();
		$this->clearCssFiles();
		return $this;
	}
}