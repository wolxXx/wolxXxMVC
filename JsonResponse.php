<?
/**
 * default and standard json response
 *
 * @author wolxXx
 * @version 1.0
 * @package wolxXxMVC
 */
class JsonResponse{
	/**
	 * status code
	 * @var integer
	 */
	public static $ok = 200;

	/**
	 * status code
	 * @var integer
	 */

	/**
	 * status code
	 * @var integer
	 */
	public static $badRequest = 503;

	/**
	 * status code
	 * @var integer
	 */
	public static $forbidden = 403;

	/**
	 * status code
	 * @var integer
	 */
	public static $notFound = 404;

	/**
	 * http like status code
	 * @var integer
	 */
	public $status = 200;

	/**
	 * if there was an error
	 * @var boolean
	 */
	public $error = false;

	/**
	 * the transport data
	 * @var array
	 */
	public $data = array();

	/**
	 * optional message
	 * @var string
	 */
	public $message = '';

	/**
	 * converts the class to a json object
	 * @return string
	 */
	public function toJSON(){
		return json_encode($this);
	}

	/**
	 * adds data to the data array
	 * @param string $key
	 * @param something $value
	 * @return JsonResponse
	 */
	public function addData($key, $value){
		$this->data[$key] = $value;
		return $this;
	}

	/**
	 * setter for the error status
	 * @param boolean $value
	 * @return JsonResponse
	 */
	public function setError($value){
		$this->error = true === $value;
		return $this;
	}

	/**
	 * setter for the status code
	 * @param integer $value
	 * @return JsonResponse
	 */
	public function setStatus($value){
		$this->status = intval($value);
		return $this;
	}

	/**
	 * clears all set data
	 * @return JsonResponse
	 */
	public function clearData(){
		$this->data = array();
		return $this;
	}

	/**
	 * setter for the message field
	 * @param string $value
	 * @return JsonResponse
	 */
	public function setMessage($value){
		$this->message = $value;
		return $this;
	}

	/**
	 * clears the message
	 * @return JsonResponse
	 */
	public function clearMessage(){
		return $this->setMessage('');
	}
}