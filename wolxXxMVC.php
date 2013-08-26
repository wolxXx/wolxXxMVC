<?
/**
 * initialises everything usefull like config, auto-loading, etc
 * then run the bootstrap class methos beforeRun, run, afterRun
 * the call hooks beforeView and afterView, in between call the view loader
 * if anything happens and an exception is thrown and not catched by causer, it will be catched here
 *
 * @version 2.2
 * @author wolxXx
 * @package wolxXxMVC
 * @todo maybe this can be classes?!
 */

final class wolxXxMVC{
	/**
	 * constructor
	 */
	public function __construct(){
		$this->run();
	}

	/**
	 * initliaises the autoloader
	 * runs all steps from bootstrap process:
	 * 	beforeRun, run, afterRun, beforeView, view, afterView
	 * catches exceptions:
	 * 	DBException, QueryGeneratorException, NoViewException, ApocalypseException, Exception
	 */
	public function run(){
		try{
			require_once 'Autoloader.php';
			new AutoLoader();
			$bootstrap = new Bootstrap();
			$bootstrap->beforeRun();
			$bootstrap->run();
			$bootstrap->afterRun();
			$bootstrap->beforeView();
			$bootstrap->view();
			$bootstrap->afterView();
		}catch(DBException $x){
			Helper::logerror('got no database connection: '.$x->getMessage());
			require_once 'tot.html';
		}catch(QueryGeneratorException $x){
			Helper::logerror('the query generator failed!! '.$x->getMessage());
			Helper::debug($x);
			require_once 'tot.html';
		}catch(NoViewException $x){
			Helper::logerror('no view or cms found');
			$this->catchError('/error/noView');
			die('');
		}catch(ApocalypseException $x){
			if('production' === Stack::getInstance()->get('version')){
				Helper::logerror($x->getMessage());
				die('Schlimmer Fehler.');
			}
			throw $x;
		}catch(Exception $x){
			if(true === Helper::isDebugEnabled()){
				self::displayError($x->getCode(), $x->getMessage(), $x->getFile(), $x->getLine(), $x->getTrace());
			}
			Helper::logerror('exeption in inital try catch: '.$x->getMessage());
			$this->catchError('/error/app');
			return;
		}
	}

	/**
	 * catches an error and tries to generate the error views
	 *
	 * @param string $newURI
	 */
	public function catchError($newURI = '/error/app'){
		$_SERVER['REQUEST_URI'] = $newURI;
		Load::clearInstance();
		try{
			$this->run();
		}catch(Exception $x){
			die('too many errors oO');
		}
	}

	/**
	 * displays the error and dies
	 *
	 * @param integer $code
	 * @param string $message
	 * @param string $file
	 * @param integer $line
	 * @param mixed $context
	 */
	public static function displayError($code, $message, $file, $line, $context = null){
		?>
			<h1>oO an error occured</h1>
			<h2><?= $message ?></h2>
			Typ: <?= $code.' '.Helper::errorCodeToString($code) ?><br />
			<?= $file ?> : <?= $line ?>
		<?
		var_dump($context);
		die();
	}

	/**
	 * catches the exception that is thrown in error handler
	 * @throws Exception
	 */
	public static function catchException(){
		$exception = func_get_arg(0);
		if(true === Helper::isDebugEnabled()){
			self::displayError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTrace());
		}
		Helper::logerror($exception->getMessage());
		die('Schlimmer Fehler.');
		die();
	}

	public static function errorHandler($code, $message, $file, $line, $context = null){
		if(true === Helper::isDebugEnabled()){
			self::displayError($code, $message, $file, $line, $context);
		}
		throw new Exception($message, $code);
	}
}

/**
 * just run the constructor.
 */
new wolxXxMVC();