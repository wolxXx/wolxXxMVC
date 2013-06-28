<?
if('' !== session_id()){
	session_destroy();
}
require_once __DIR__.'/../../../application/config/defines.php';
require_once __DIR__.'/../Autoloader.php';
$path = __DIR__.'/../../../';
require_once __DIR__.'/../CoreConfig.php';
require_once $path.'application/config/AppConfig.php';
require_once $path.'application/config/HostConfig.php';
$autoLoaderPath = realpath($path);
new AutoLoader($autoLoaderPath);
/**
 * @codeCoverageIgnore
 */
class Helper extends CoreHelper{
	public static function refresh(){
		die('');
	}
	public static function redirect($url = null){
		die('');
	}

	public static function logerror($msg){
		echo $msg;
	}
}

$config = new HostConfig();
$config->configureApplication();
$config->configureHost();
Stack::getInstance()->set('debug', false);
