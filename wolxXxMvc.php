<?
/**
 * initialises everything usefull like config, auto-loading, etc
 * then run the bootstrap class methos beforeRun, run, afterRun
 * the call hooks beforeView and afterView, in between call the view loader
 * if anything happens and an exception is thrown and not catched by causer, it will be catched here
 *
 * @version 2.1
 * @author wolxXx
 * @package wolxXxMVC
 * @todo maybe this can be classes?!
 */

class wolxXxMVC{
	public function __construct(){
		$this->run();
	}

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
			die($x->getMessage());
		}catch(Exception $x){
			if(true === Helper::isDebugEnabled()){
				Helper::dieDebug($x);
			}
			Helper::logerror('exeption in inital try catch: '.$x->getMessage());
			$this->catchError('/error/app');
			return;
		}
	}

	public function catchError($newURI = '/error/app'){
		$_SERVER['REQUEST_URI'] = $newURI;
		Load::clearInstance();
		try{
			$this->run();
		}catch(Exception $x){
			die('too many errors oO');
		}
	}
}

new wolxXxMVC();