<?
/**
 * just a simple query string class
 * implementing the QueryStringInterface
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 */
class QueryString implements QueryStringInterface{
	/**
	 * the query string
	 *
	 * @var string
	 */
	protected $querystring;

	/**
	 * constructor
	 *
	 * @param string $queryString
	 */
	public function __construct($queryString = null){
		$this->setQueryString($queryString);
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryStringInterface::setQueryString()
	 */
	public function setQueryString($string){
		$this->querystring = $string;
	}

	/**
	 * (non-PHPdoc)
	 * @see QueryStringInterface::getQueryString()
	 */
	public function getQueryString($cleared = false){
		if(true === $cleared){
			$this->querystring = str_replace(array("\n", "\t"), array(' ', ''), $this->querystring);
			while(false !== strstr($this->querystring, '  ')){
				$this->querystring = str_replace('  ', ' ', $this->querystring);
			}
			$this->querystring = str_replace(' ;', ';', $this->querystring);
			$this->querystring = trim($this->querystring, ' ');
		}
		return $this->querystring;
	}
}