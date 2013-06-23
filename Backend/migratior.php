<?
/**
 * migration script
 *
 * @version 2.0
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Exceptions
 */
#cd here
#get mysql connection
#get latest migration
#run all other migrations

error_reporting(-1);
ini_set('display_errors', '1');

function secho($elem){
	echo "$elem\n";
}

function auRevoir(){
	secho('That\'s it! seeeya, dude!');
	exit();
}

secho("");
secho("");
secho('wolxXxMVC deploy tools: Migrations.');

chdir(__DIR__);
chdir('../../../');

if('' === $_SERVER['DOCUMENT_ROOT']){
	$_SERVER['DOCUMENT_ROOT'] = getcwd();
}

require_once __DIR__.'/../Autoloader.php';
new AutoLoader();

$bootstrap = new Bootstrap();

ini_set('error_log', 'log/deploymenterror.log');
ini_set('display_errors', 1);
ini_set('error_reporting', 'E_ALL');
error_reporting(-1);

if(false === isset($_SERVER['argv'][1])){
	error_log('mode not set! take dev or production!');
	die('unable to migrate. please check '.ini_get('error_log').PHP_EOL);
}

putenv('APPLICATION_ENV=main-'.$_SERVER['argv'][1]);
if(false === getenv('APPLICATION_ENV')){
	putenv('APPLICATION_ENV=main-production');
}

$stack = Stack::getInstance();
$stack->set('debug', '1');
$stack->set('disable_db_log', '0');

$model = new Model();

$obj = $model->find(array(
	'from' => 'migrations',
	'order' => ' number DESC',
	'method' => 'one'
));

secho("Current migration in database: ".$obj->number);
$dir = opendir('application/migrations');
$entries = array();
while($current = readdir($dir)){
	if(in_array($current, array('.', '..', '.svn'))){
		continue;
	}
	$pieces = explode('.', $current, 2);
	$entries[$pieces[0]] = $current;
}

closedir($dir);
asort($entries);
end($entries);

if(key($entries) == $obj->number){
	secho('Latest migration reached ('.$obj->number.') nothing to do here');
	auRevoir();
}
reset($entries);
$entries = array_slice($entries, $obj->number, sizeof($entries) +1 );
foreach($entries as $key => $value){
	secho('Running now migration '.$key);
	$instance = Migration::getInstance($key);
	$instance->run();
	$instance->afterRun();
	secho('Migration '.$key.': done....');
}
auRevoir();