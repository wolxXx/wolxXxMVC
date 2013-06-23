<?
/**
 * request class
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @version 1.0
 */
class Request{
	/**
	 * flag if the request was made by an ajax call
	 * @var boolean
	 */
	protected $isAjax;

	/**
	 * flag if the request has post data
	 * @var boolean
	 */
	protected $isPost;

	/**
	 * flag if the request was made by a mobile device
	 * or the application environment is set to mobile
	 * @var boolean
	 */
	protected $isMobile;

	/**
	 * the url path
	 * @var string
	 */
	protected $path;

	/**
	 * an instance of a data object
	 * @var DataObject
	 */
	public $dataObject;

	/**
	 * an instance of the stack
	 * @var Stack
	 */
	protected $stack;

	/**
	 * constructor
	 * initializes via init method
	 */
	public function __construct(){
		$this->init();
	}

	/**
	 * grabs the instance of the stack
	 * creates a new data object instance
	 * checks if request is ajax, post and mobile
	 */
	protected function init(){
		$this->stack = Stack::getInstance();
		$this->dataObject = new DataObject();
		$this->checkIfRequestIsAjax();
		$this->checkIfRequestIsPost();
		$this->checkIfRequestIsMobile();
	}

	/**
	 * checks if the request was made via ajax
	 * @return Request
	 */
	protected function checkIfRequestIsAjax(){
		$this->isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']);
		return $this;
	}

	/**
	 * checks if the request contains post data
	 * @return Request
	 */
	protected function checkIfRequestIsPost(){
		$post = $this->dataObject->getRawPOST();
		$this->isPost = false === empty($post);
		return $this;
	}

	/**
	 * checks if the request was made from a mobile device
	 * or the application environment was set to mobile
	 * @return Request
	 */
	protected function checkIfRequestIsMobile(){
		$detect = new Mobile_Detect();
		if($detect->isMobile() || 'mobile' === $this->stack->get('version')){
			$this->isMobile = true;
		}else{
			$this->isMobile = false;
		}
		return $this;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isAjax(){
		return $this->isAjax;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isPost(){
		return $this->isPost;
	}

	/**
	 *
	 * @return boolean
	 */
	public function isMobile(){
		return $this->isMobile;
	}

	/**
	 * logs the post data
	 * fields named "pass", "password" or "passwort" are replaced with ****
	 * @param string $destination
	 * @return Request
	 */
	public function postLog($destination){
		if(false === $this->isPost()){
			return $this;
		}
		if(false === isset($_SERVER['REMOTE_ADDR'])){
			$_SERVER['REMOTE_ADDR'] = 'localhost';
		}

		if(false === isset($_SERVER['REQUEST_URI'])){
			$_SERVER['REQUEST_URI'] = 'localhost';
		}
		$delim = ' | ';
		$txt = Helper::getDate().$delim;
		$txt .= $_SERVER['REMOTE_ADDR'].$delim;
		$txt .= true === Auth::isLoggedIn()? 'true ('.Auth::getUserNick().')'.$delim : 'false'.$delim;
		$txt .= $_SERVER['REQUEST_URI'].$delim;
		$txt .= "\nvalues:\n";
		foreach($this->dataObject->getRawPOST() as $key => $value){
			if(in_array($key, array('pass', 'password', 'passwort'))){
				$value = '****';
			}
			if(in_array($key, array('base64data'))){
				$value = '[base64data length: '.strlen($value).']';
			}
			if(true === is_array($value)){
				$newvalue = '';
				foreach($value as $x => $y){
					$newvalue .= "$x = $y, ";
				}
				$value = trim($newvalue);
			}
			$txt .= "$key: $value\n";
		}
		$txt .= "________________\n";
		Helper::logToFile($txt, $destination);
		return $this;
	}
}