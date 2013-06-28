<?
/**
 * collection of html element generators
 *
 * @author wolxXx
 * @version 0.2
 * @package wolxXxMVC
 * @subpackage HTML
 */
class CoreHTML{
	/**
	 * creates a new dom html element
	 * fills all args into data object
	 *
	 * @param string $type
	 * @param array $args
	 * @throws Exception
	 * @return DomElementAbstract
	 */
	public static function Factory($type, $args = array()){
		$type = ucfirst($type);
		$object = new $type();
		if(!$object instanceof DomElementAbstract){
			throw new Exception('"'.$type.'" is not a supported class from html factory');
		}
		$object->init();
		foreach($args as $key => $value){
			$object->set($key, $value);
		}
		return $object;
	}

	/**
	 * generates a unique id string and appends it to id and name
	 * so every element has a unique id and name
	 *
	 * @return array
	 */
	public static function getUniqueIdAndName(){
		$id = uniqid();
		return array(
			'id' => 'dom_elem_id_'.$id,
			'name' => 'dom_elem_name_'.$id
		);
	}

	/**
	 * merges the default configuration arrays with the given
	 * returns a simple object for having object access
	 *
	 * @param array $conf
	 * @return StdClass
	 */
	protected static function mergeConf($conf){
		$trace = debug_backtrace();
		$function = $trace[1]['function'];
		$default = DomElementAbstract::getDefaultConf();
		switch($function){
			case 'renderFormStart':{
				$default = array_merge($default, Form::getDefaultConf());
			}break;
			case 'renderInput':{
				$default = array_merge($default, Input::getDefaultConf());
			}break;
			case 'renderPassword':{
				$default = array_merge($default, Password::getDefaultConf());
			}break;
			case 'renderSubmit':{
				$default = array_merge($default, Submit::getDefaultConf());
			}break;
			case 'renderTextarea':{
				$default['rows'] = 1000;
				$default['cols'] = 1000;
				$default['text'] = '';
			}break;
		}
		return (object) array_merge($default, $conf);
	}

	/**
	 * opens a single tag like <img />
	 *
	 * @param unknown_type $name
	 * @param unknown_type $args
	 * @return string
	 */
	public static function openSingleTag($name, $args = array()){
		$return = sprintf('<%s', $name);
		foreach($args as $key => $value){
			if(null === $value){
				continue;
			}
			$return .= sprintf(' %s="%s"', $key, $value);
		}
		return $return;
	}

	/**
	 * opens a tag like <textarea>
	 *
	 * @param string $name
	 * @param array $args
	 * @return string
	 */
	public static function openTag($name, $args = array()){
		return self::openSingleTag($name, $args).'>';
	}

	/**
	 * closes a tag like </textarea>
	 *
	 * @param string $name
	 * @return string
	 */
	public static function closeTag($name){
		return sprintf('</%s>', $name);
	}

	/**
	 * closes a single tag like <img />
	 *
	 * @return string
	 */
	public static function closeSingleTag(){
		return ' />';
	}

	/**
	 * renders a textarea
	 * conf array can contain id, name, rows, cols, class, text
	 *
	 * @param array $conf
	 */
	public static function renderTextarea($conf = array()){
		$conf = self::mergeConf($conf);
		echo self::openTag('textarea', array(
			'id' => $conf->id,
			'name' => $conf->name,
			'rows' => $conf->rows,
			'cols' => $conf->cols,
			'class' => $conf->class
		));
		echo $conf->text;
		echo self::closeTag('textarea');
	}

	/**
	 * renders a submit button
	 * conf array can contain id, name, value, class
	 *
	 * @param array $conf
	 */
	public static function renderSubmit($conf = array()){
		$conf = self::mergeConf($conf);
		echo self::openSingleTag('input', array(
			'class' => $conf->class,
			'type' => 'submit',
			'id' => $conf->id,
			'name' => $conf->name,
			'value' => $conf->value
		));
		echo self::closeSingleTag();
	}

	/**
	 * renders an input field
	 * conf array can contain id, name, value, class, placeholder, type
	 *
	 * @param array $conf
	 */
	public static function renderInput($conf = array()){
		$conf = self::mergeConf($conf);
		echo self::openSingleTag('input', array(
			'class' => $conf->class,
			'type' => $conf->type,
			'id' => $conf->id,
			'name' => $conf->name,
			'value' => $conf->value
		));
		echo self::closeSingleTag();
	}

	/**
	 * renders a password input
	 * conf array can contain id, name
	 *
	 * @param array $conf
	 */
	public static function renderPassword($conf = array()){
		$conf = self::mergeConf($conf);
		echo self::openSingleTag('input', array(
			'id' => $conf->id,
			'name' => $conf->name,
			'type' => 'password',
		));
		echo self::closeSingleTag();
	}

	/**
	 * renders the start tag for a form element
	 * conf array can contain id, class, action, method
	 *
	 * @param array $conf
	 */
	public static function renderFormStart($conf = array()){
		$conf = self::mergeConf($conf);
		echo self::openTag('form', array(
			'method' => $conf->method,
			'action' => $conf->action,
			'class' => $conf->class,
			'id' => $conf->id
		));
	}

	/**
	 * renders a closing form tag
	 */
	public static function renderFormClose(){
		echo self::closeTag('form');
	}

	/**
	 * renders a label element
	 * conf array can contain id, class, for
	 *
	 * @param array $conf
	 */
	public static function renderLabel($conf = array()){
		$conf = self::mergeConf($conf);
		echo self::openTag('label', array(
			'for' => $conf->for,
			'id' => $conf->id,
			'class' => $conf->class
		));
		echo $conf->text;
		echo self::closeTag('label');
	}
}