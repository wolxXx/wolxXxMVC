<?

class ApiStatus{

	public static $success = 0;

	public static function getStatusTextForCode($code){
		switch ($code){
			case self::$success: {
				return 'ok';
			}break;
			default:{
				$reflection = new ReflectionClass(__CLASS__);
				foreach($reflection->getProperties(ReflectionProperty::IS_STATIC) as $current){
					$current = $current->name;
					if($code === self::$$current){
						Helper::logToFile(sprintf('need message for code =  %s (%s) in getstatustextfor code in apistatus', $code, $current), 'apistatuslog');
						return $current;
					}
				}
				throw new Exception(sprintf('ApiCode "%s" not found', $code));
			}break;
		}
	}
}