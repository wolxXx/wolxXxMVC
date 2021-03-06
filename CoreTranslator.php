<?
/**
 * translator class for translating
 * usage as static helper mehtods
 *
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 * @todo provide database, file, json, whatever for translation files
 * @todo do not return just the same string ;)
 */
class CoreTranslator{
	/**
	 * translates the given string. accepts sprintf args
	 * like %s for replacing arguments
	 *
	 * @param string $string
	 * @param string $args
	 * @return string
	 * @throws TranslatorException
	 */
	public static function translate($string, $args = null){
		$return = false;
		try{
			$return = @call_user_func_array('sprintf', func_get_args());
		}catch(Exception $x){
			$return = false;
		}
		if(false === $return){
			throw new TranslatorException('Translator failed! args = '.implode(' | ', func_get_args()));
		}
		$return = nl2br($return);
		return $return;
	}
}