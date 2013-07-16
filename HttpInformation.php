<?
/**
 * Wrapper for HTTP information
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @version 0.1
 *
 */
class HttpInformation{
	/**
	 * status text, like OK, not found, etc
	 * @var string
	 */
	public $statusText;

	/**
	 * status code, like 404, 200, 304, etc
	 * @var integer
	 */
	public $statusCode;

	/**
	 * the type of the returned source, like image/jpeg, text/html, etc
	 * @var string
	 */
	public $contenttype;

	/**
	 * the url of the http ressource
	 * @var string
	 */
	public $url;

	/**
	 * the size of the returned source in kbyte
	 * @var integer
	 */
	public $size;

	/**
	 * constructor
	 * @param string $url
	 */
	public function __construct($url){
		$this->url = $url;
		$this->getInformation();
	}

	/**
	 * grabs the headers and saves the information
	 */
	protected function getInformation(){
		if(false === Helper::isURLSyntaxOk($this->url)){
			throw new Exception('url syntax not ok: '.$this->url);
		}
		$headers = get_headers($this->url, 1);
		$status = explode(' ', $headers[0]);
		$this->statusCode = (int)$status[1];
		if(404 === $this->statusCode){
			$this->statusText = 'Not found';
			$this->contenttype = null;
			$this->size = 0;
			return;
		}
		$this->statusText = $status[2];
		$this->contenttype = $headers['Content-Type'];
		$this->size = (int)($headers['Content-Length'] / 1024);
	}

}