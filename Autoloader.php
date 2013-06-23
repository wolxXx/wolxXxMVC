<?
/**
 * object orientated autoloader
 *
 * @author wolxXx
 * @version 2.0
 * @package wolxXxMVC
 */
class AutoLoader{

	/**
	 * the found paths in the application directory
	 * @var array
	 */
	public static $paths = array();

	/**
	 * ignore these paths
	 * @var array
	 */
	protected $excludePaths = array(
		'Lib/wolxXxMVC/Setup/',
		'Lib/wolxXxMVC/Setup/src/',
		'Lib/wolxXxMVC/tests/',
		'files/',
		'log/',
		'tmp/',
		'css/',
		'js/',
		'img/',
		'views/',
		'tests/'
	);

	/**
	 * constructor
	 * grabs the paths, registers spl autoloader
	 *
	 * if the $path param is null it takes get current working directory!
	*/
	public function __construct($path = null){
		if(null === $path){
			$path = getcwd();
		}
		if(false === isset($_SERVER['DOCUMENT_ROOT']) || '' === $_SERVER['DOCUMENT_ROOT']){
			$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__.'../../../');
		}
		$this->grabPaths($path.DIRECTORY_SEPARATOR);
		self::$paths = array_unique(self::$paths);
		spl_autoload_register(array($this, 'loadClass'));
	}

	/**
	 * scans the application directory for subdirectories
	 * @param string $directory
	 */
	protected function grabPaths($directory){
		if(false === is_dir($directory)){
			return;
		}
		if(true === in_array(str_replace($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR, '', $directory), $this->excludePaths)){
			return;
		}
		self::$paths[] = $directory;
		foreach(glob($directory.'*/') as $current){
			if(true === in_array(str_replace($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR, '', $current), $this->excludePaths)){
				return;
			}
			$this->grabPaths($current);
		}
	}

	/**
	 * determines if the requested class name can be loaded via the autoloader
	 * @param string $className
	 * @return boolean
	 */
	public static function isLoadable($className){
		foreach(self::$paths as $path){
			if(true === in_array(true, array(is_file("$path/$className.php"),is_file($path.'/'.strtolower($className).'.php'),is_file($path.'/'.ucfirst($className).'.php')))){
				return true;
			}
		}
		return false;
	}

	/**
	 * loads a requested file
	 * @param string $className
	 * @throws Exception
	 */
	public function loadClass($className){
		$found = false;
		foreach(self::$paths as $path){
			if(is_file("$path/$className.php")){
				require_once("$path/$className.php");
				return;
			}elseif(is_file($path.'/'.strtolower($className).'.php')){
				require_once($path.'/'.strtolower($className).'.php');
				return;
			}elseif(is_file($path.'/'.ucfirst($className).'.php')){
				require_once($path.'/'.ucfirst($className).'.php');
				return;
			}
		}
	}
}