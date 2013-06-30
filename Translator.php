<?
class Translator{
	public static function translate($string, $args = null){
		Helper::logToFile($string.' args: '.implode('#', true === is_array($args)? $args : array()), 'translations');
		return sprintf($string, $args);
	}
}