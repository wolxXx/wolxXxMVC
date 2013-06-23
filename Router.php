<?
/**
 * prototype and helper for routing
 * 
 * @author wolxXx
 * @version 1.1
 * @package wolxXxMVC
 */
class Router{
	/**
	 * contains chars that cannot be displayed correctly in urls
	 * @var array
	 */
	public static $search = array(
		'/ä/','/Ä/','/ö/','/Ö/','/ü/','/Ü/','/ß/','/ /','/[^a-zA-Z0-9_-]/u'
	);
	/**
	 * replace chars 
	 * @var array
	 */
	public static $replace = array(
		"ae","Ae","oe","Oe", "ue","Ue","ss","-",""
	);
	
	/**
	 * adds an url to the router
	 * @param string $key
	 * @param string $value
	 */
	public function addRoute($key, $value){
		$this->routes[$key] = $value;
	}
	
	/**
	 * retrieves a route for a given key or null if nothing found
	 * @param string $key
	 * @return string | null
	 */
	public function getRoute($key){
		if(array_key_exists($key, $this->routes)){
			return $this->routes[$key];
		}
		return null;
	}
	
	/**
	 * returns all defined routes
	 * @return array
	 */
	public function getAllRoutes(){
		return $this->routes;
	}
		
	/**
	 * replaces bad cars for having a clear url 
	 * @param string $string
	 * @return string
	 */
	public static function clearForUrl($string){
		return rtrim(preg_replace(self::$search, self::$replace, $string), '-');
	}
}