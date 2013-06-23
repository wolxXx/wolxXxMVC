<?
/**
 * pattern for a migration
 * does a single migration
 *
 * @author wolxXx
 * @package wolxXxMVC
 * @subpackage Database
 * @version 1.2
 *
 */
abstract class Migration{
	/**
	 * continious number
	 * make sure, the revision is really continious!!
	 * @var integer
	 */
	protected $revision;
	/**
	 * a direct connection to the database
	 * @var Connection
	 */
	protected $connection;

	/**
	 * constructor
	 */
	public function __construct($revision = null){
		$this->setRevision($revision);
		$this->connection = DatabaseManager::getInstance()->getConnection();
	}
	/**
	 * setter for the revision number
	 * @param integer $revision
	 * @return Migration
	 */
	public function setRevision($revision){
		$this->revision = $revision;
		return $this;
	}
	/**
	 * getter for the revision number
	 * @return integer
	 */
	public function getRevision(){
		return $this->revision;
	}

	/**
	 * after run hook
	 * @return Migration
	 */
	public final function afterRun(){
		$this->insertMigrationToDB();
		return $this;
	}

	/**
	 * inserts migration number on top into database table migrations
	 * @return Migration
	 */
	protected function insertMigrationToDB(){
		$save = new SaveObject('migrations');
		$save->number = $this->getRevision();
		$save->created = Helper::getDate();
		$save->save();
		return $this;
	}

	/**
	 * this is where the main procedure takes place. drop your code here!
	 */
	public abstract function run();

	/**
	 * returns a new instance of a migration
	 * @param integer $revision
	 */
	public static function getInstance($revision){
		$className = 'Migration'.$revision;
		if(!class_exists($className, false)){
			require_once 'application/migrations/'.$revision.'.php';
		}
		$instance = new $className();
		$instance->setRevision($revision);
		return $instance;
	}
}