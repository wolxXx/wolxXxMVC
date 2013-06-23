<?

abstract class CoreDAO extends KeyValueStore{
	public abstract function create();
	public abstract function save();
	protected $isDirty;
	function setData($data){
		parent::setData($data);
		$this->isDirty = true;
	}
	function set($key, $value){
		parent::set($key, $value);
		$this->isDirty = true;
	}
}