<?
class Translator{
	public static function translate($string, $args = null){
		return sprintf($string, $args);
	}
}