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
<<<<<<< HEAD
	 * @throws TranslatorException
=======
<<<<<<< HEAD
	 * @throws TranslatorException
=======
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
	 */
	public static function translate($string, $args = null){
		$return = false;
		try{
			$return = @call_user_func_array('sprintf', func_get_args());
		}catch(Exception $x){
			$return = false;
		}
		if(false === $return){
<<<<<<< HEAD
			throw new TranslatorException('Translator failed! args = '.implode(' | ', func_get_args()));
=======
<<<<<<< HEAD
			throw new TranslatorException('Translator failed! args = '.implode(' | ', func_get_args()));
=======
			throw new Exception('Translator failed! args = '.implode(' | ', func_get_args()));
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
		}
		return $return;
	}
}