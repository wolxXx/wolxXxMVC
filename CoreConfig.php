<?
/**
 * config base for having objects as config
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @version 1.0
 */
abstract class CoreConfig{
	/**
	 * an instance of the stack
	 * @var Stack
	 */
	protected $stack;

	/**
	 * get an instance of the stack
	 */
	public final function __construct(){
		$this->stack = Stack::getInstance();
	}

	/**
	 * configuration of the application
	 */
	public function configureApplication(){
		throw new ApocalypseException('please configure application');
	}

	/**
	 * configuration of the host
	 * place database credentials here
	 * configure whatever you want
	 */
	public function configureHost(){
		throw new ApocalypseException('please configure host');
	}

	/**
	 * checks if the minimal needed settings are done
	 * @throws Exception
	 */
	public final function checkConfig(){
		$needed = array(
			$this->stack->get('db_host'),
			$this->stack->get('db_user'),
			$this->stack->get('db_table'),
			$this->stack->get('db_pass')
		);
		if(true === in_array(null, $needed)){
			throw new ApocalypseException('you need to specify db_host, db_user, db_table, db_pass!');
		}
	}
}