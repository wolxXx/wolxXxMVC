<?
/**
 * initialises everything usefull like config, auto-loading, etc
 * then run the bootstrap class methos beforeRun, run, afterRun
 * the call hooks beforeView and afterView, in between call the view loader
 * if anything happens and an exception is thrown and not catched by causer, it will be catched here
 *
 * @version 2.0
 * @author wolxXx
 * @package wolxXxMVC
 */
function initialRun(){
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
		$_SERVER['REQUEST_URI'] = '/error/noView';
		Load::clearInstance();
		initialRun();
		die();
	}catch(ApocalypseException $x){
			if('production' === Stack::getInstance()->get('version')){
				Helper::logerror($x->getMessage());
				die('Schlimmer Fehler.');
			}
			die($x->getMessage());
	}catch(Exception $x){
		Helper::logerror('exeption in inital try catch: '.$x->getMessage());
		$_SERVER['REQUEST_URI'] = '/error/app';
		initialRun();
		die();
	}
}
initialRun();