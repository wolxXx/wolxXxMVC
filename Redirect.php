<?
/**
 * redirect helper class
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 */
class Redirect{
	/**
	 * the url that should be redirect to
	 * @var string
	 */
	protected $url;

	/**
	 * type of redirection
	 * @var string
	 * @see Redirect::$redirect, Redirect::$moved, Redirect::$refresh, Redirect::$historyBack
	 */
	protected $method;

	/**
	 * redirect type
	 * @var string
	 */
	public static $redirect = 'redirect';

	/**
	 * redirect type
	 * @var string
	 */
	public static $moved = 'moved';

	/**
	 * redirect type
	 * @var string
	 */
	public static $refresh = 'refresh';

	/**
	 * redirect type
	 * @var string
	 */
	public static $historyBack = 'historyBack';

	/**
	 * constructor
	 * @param string $url
	 * @param string $method
	 */
	public function __construct($url, $method = null){
		$this->setMethod($method);
		$this->setUrl($url);
	}

	/**
	 * setter for the redirect url
	 * @param string $url
	 * @return Redirect
	 */
	public function setUrl($url){
		$this->url = $url;
		return $this;
	}

	/**
	 * getter for the redirect url
	 * @return string
	 */
	public function getUrl(){
		return $this->url;
	}

	/**
	 * setter for the redirection type
	 * default is direct redirect
	 * @param string | null $method
	 * @return Redirect
	 */
	public function setMethod($method = null){
		if(null === $method || false === isset(self::$$method)){
			$method = self::$redirect;
		}
		$this->method = $method;
		return $this;
	}

	/**
	 * getter for the redirect type
	 * @return string
	 */
	public function getMethod(){
		return $this->method;
	}

	/**
	 * the redirect caller
	 * calls the CoreHelper functions
	 */
	public function redirect(){
		call_user_func('Helper::'.$this->method, $this->url);
	}
}