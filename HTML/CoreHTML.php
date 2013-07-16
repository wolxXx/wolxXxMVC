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
		$uniqueId = uniqid();
		return array(
			'id' => 'dom_elem_id_'.$uniqueId,
			'name' => 'dom_elem_name_'.$uniqueId
		);
	}

	/**
	 * merges the default configuration arrays with the given
	 * returns a simple object for having object access
	 *
	 * @param array $conf
	 * @param array $default
	 * @return StdClass
	 */
	public static function mergeConf($conf, $default = null){
		$trace = debug_backtrace();
		$function = $trace[1]['function'];
<<<<<<< HEAD
=======
<<<<<<< HEAD
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
		if(null === $default){
			$default = DomElementAbstract::getDefaultConf();
			switch($function){
				case 'renderButton':{
					$default = array_merge($default, Button::getDefaultConf());
				}break;
				case 'renderCheckbox':{
					$default = array_merge($default, Checkbox::getDefaultConf());
				}break;
				case 'renderOption':{
					$default = array_merge($default, DropdownElement::getDefaultConf());
				}break;
				case 'renderFormStart':{
					$default = array_merge($default, Form::getDefaultConf());
				}break;
				case 'renderHeadline':{
					$default = array_merge($default, Headline::getDefaultConf());
				}break;
				case 'renderInput':{
					$default = array_merge($default, Input::getDefaultConf());
				}break;
				case 'renderLabel':{
					$default = array_merge($default, Label::getDefaultConf());
				}break;
				case 'renderOptionGroupStart':{
					$default = array_merge($default, DropdownGroup::getDefaultConf());
				}break;
				case 'renderPassword':{
					$default = array_merge($default, Password::getDefaultConf());
				}break;
				case 'renderRadioOption':{
					$default = array_merge($default, RadioOption::getDefaultConf());
				}break;
				case 'renderSelectStart':{
					$default = array_merge($default, Dropdown::getDefaultConf());
				}break;
				case 'renderSpan':{
					$default = array_merge($default, Span::getDefaultConf());
				}break;
				case 'renderSubmit':{
					$default = array_merge($default, Submit::getDefaultConf());
				}break;
				case 'renderTextarea':{
					$default = array_merge($default, Textarea::getDefaultConf());
				}break;
			}
<<<<<<< HEAD
=======
=======
		$default = DomElementAbstract::getDefaultConf();
		switch($function){
			case 'renderBr':{
				$default = array_merge($default, Br::getDefaultConf());
			}break;
			case 'renderCheckbox':{
				$default = array_merge($default, Checkbox::getDefaultConf());
			}break;
			case 'renderClear':{
				$default = array_merge($default, Clear::getDefaultConf());
			}break;
			case 'renderOption':{
				$default = array_merge($default, DropdownElement::getDefaultConf());
			}break;
			case 'renderFormStart':{
				$default = array_merge($default, Form::getDefaultConf());
			}break;
			case 'renderGrid':{
				$default = array_merge($default, Grid::getDefaultConf());
			}break;
			case 'renderHeadline':{
				$default = array_merge($default, Headline::getDefaultConf());
			}break;
			case 'renderInput':{
				$default = array_merge($default, Input::getDefaultConf());
			}break;
			case 'renderLabel':{
				$default = array_merge($default, Label::getDefaultConf());
			}break;
			case 'renderPassword':{
				$default = array_merge($default, Password::getDefaultConf());
			}break;
			case 'renderRadio':{
				$default = array_merge($default, Radio::getDefaultConf());
			}break;
			case 'renderRadioOption':{
				$default = array_merge($default, RadioOption::getDefaultConf());
			}break;
			case 'renderSpan':{
				$default = array_merge($default, Span::getDefaultConf());
			}break;
			case 'renderSubmit':{
				$default = array_merge($default, Submit::getDefaultConf());
			}break;
			case 'renderTextarea':{
				$default = array_merge($default, Textarea::getDefaultConf());
			}break;
>>>>>>> ecb243115d7143c5c1e0ea71de4a147b66ae5e6d
>>>>>>> 633ea8bdaa55f434ab9af3f9a8ebce01551998cc
		}
		return (object) array_merge($default, $conf);
	}

	/**
	 * echoes something and prints the new line
	 *
	 * @param string $text
	 */
	public static function out(){
		foreach(func_get_args() as $current){
			echo $current.PHP_EOL;
		}
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
		self::out(
			$conf->text,
			self::closeTag('textarea')
		);
	}

	/**
	 * renders a submit button
	 * conf array can contain id, name, value, class
	 *
	 * @param array $conf
	 */
	public static function renderSubmit($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openSingleTag('input', array(
				'class' => $conf->class,
				'type' => 'submit',
				'id' => $conf->id,
				'name' => $conf->name,
				'value' => $conf->value
			)),
			self::closeSingleTag()
		);
	}

	/**
	 * renders a div class clear tag
	 */
	public static function renderClear(){
		self::out(
			self::openTag('div', array(
				'class' => 'clear'
			)),
			self::closeTag('div')
		);
	}

	/**
	 * renders a checkbox
	 */
	public static function renderCheckbox($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openSingleTag('input', array(
				'checked' => $conf->checked,
				'class' => $conf->class,
				'name' => $conf->name,
				'value' => $conf->value,
				'id' => $conf->id,
				'type' => 'checkbox'
			)),
			self::closeSingleTag()
		);
	}

	/**
	 * renders a span element
	 * @param array $conf
	 */
	public static function renderSpan($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openTag('span', array(
				'class' => $conf->class,
				'id' => $conf->id,
			)),
			$conf->text,
			self::closeTag('span')
		);
	}

	/**
	 * renders an input field
	 * conf array can contain id, name, value, class, placeholder, type
	 *
	 * @param array $conf
	 */
	public static function renderInput($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openSingleTag('input', array(
				'class' => $conf->class,
				'type' => $conf->type,
				'id' => $conf->id,
				'name' => $conf->name,
				'value' => $conf->value,
				'autocomplete' => $conf->autocomplete,
				'readonly' => true === $conf->readonly? 'readonly' : $conf->readonly
			)),
			self::closeSingleTag()
		);
	}

	/**
	 * renders a radio option
	 *
	 * @param array $conf
	 */
	public static function renderRadioOption($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openSingleTag('input', array(
				'id' => $conf->id,
				'name' => $conf->name,
				'value' => $conf->value,
				'type' => 'radio',
				'checked' => $conf->checked
			)),
			self::closeSingleTag('input')
		);
	}

	/**
	 * renders a password input
	 * conf array can contain id, name
	 *
	 * @param array $conf
	 */
	public static function renderPassword($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openSingleTag('input', array(
				'id' => $conf->id,
				'name' => $conf->name,
				'type' => 'password',
			)),
			self::closeSingleTag()
		);
	}

	/**
	 * renders a headline element
	 *
	 * @param array $conf
	 */
	public static function renderHeadline($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openTag('h'.$conf->size),
			$conf->text,
			self::closeTag('h'.$conf->size)
		);
	}

	/**
	 * renders the start tag for a form element
	 * conf array can contain id, class, action, method
	 *
	 * @param array $conf
	 */
	public static function renderFormStart($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openTag('form', array(
				'method' => $conf->method,
				'action' => $conf->action,
				'class' => $conf->class,
				'id' => $conf->id,
				'enctype' => $conf->enctype
			))
		);
	}

	/**
	 * renders a closing form tag
	 */
	public static function renderFormClose(){
		self::out(
			self::closeTag('form')
		);
	}

	/**
	 * renders a label element
	 * conf array can contain id, class, for
	 *
	 * @param array $conf
	 */
	public static function renderLabel($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openTag('label', array(
				'for' => $conf->for,
				'id' => $conf->id,
				'class' => $conf->class
			)),
			$conf->text,
			self::closeTag('label')
		);
	}

	/**
	 * renders a break tag
	 */
	public static function renderBr(){
		self::out('');
		echo self::openSingleTag('br');
		echo self::closeSingleTag();
		self::out('');
	}

	/**
	 * renders a select open tag
	 *
	 * @param array $conf
	 */
	public static function renderSelectStart($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openTag('select', array(
				'name' => $conf->name,
				'id' => $conf->id,
				'class' => $conf->class
			))
		);
	}

	/**
	 * renders a closing select tag
	 */
	public static function renderSelectClose(){
		self::out(
			self::closeTag('select')
		);
	}

	/**
	 * renders an option group start tag
	 *
	 * @param array $conf
	 */
	public static function renderOptionGroupStart($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openTag('optgroup', array(
				'name' => $conf->name,
				'id' => $conf->id,
				'label' => $conf->label
			))
		);
	}

	/**
	 * renders a closing option group tag
	 */
	public static function renderOptionGroupClose(){
		self::out(
			self::closeTag('optgroup')
		);
	}

	/**
	 * renders an option element
	 *
	 * @param array $conf
	 */
	public static function renderOption($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openTag('option', array(
				'value' => $conf->value,
				'id' => $conf->id,
				'selected' => $conf->selected
			)),
			$conf->text,
			self::closeTag('option')
		);
	}

	/**
	 * renders a button start tag
	 *
	 * @param array $conf
	 */
	public static function renderButton($conf = array()){
		$conf = self::mergeConf($conf);
		self::out(
			self::openTag('button', array(
				'id' => $conf->id,
			)),
			$conf->text,
			self::closeTag('button')
		);
	}
}