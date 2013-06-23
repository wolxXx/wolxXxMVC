<?
/**
 * backend management tool
 * CRUD for models.
 *
 *
 * @author wolxXx
 * @version 2.0
 * @package application
 * @subpackage controllers
 */
class AdminController extends CoreController{
	/**
	 * (non-PHPdoc)
	 * @see CoreController::setAccessRules()
	 */
	function setAccessRules(){
		$this->accessChecker->addRule(new AccessRule('*', true, USER_TYPE_ADMIN));
	}

	/**
	 * (non-PHPdoc)
	 * @see CoreController::postlog()
	 */
	function postlog(){
		//do nothing
		#nüscht!#
		/**
		 * janüscht!
		 */
		/*
		 * nichma een bisschen junge!
		 */
	}

	/**
	 * index pages lists new users and all database tables
	 */
	function indexAction(){
		$tables = DatabaseInformation::getAllTables(DatabaseManager::getInstance()->getConnection()->getDatabase());
		$this->load->set('models', $tables);
	}

	/**
	 * lists all entities in a database table ordered by id desc
	 */
	function listingAction(){
		$entries = $this->model->find(array(
			'from' => func_get_arg(2),
			'order' => 'id DESC'
		));
		$this->load->set('entries', $entries);
		$this->load->set('modeltype', func_get_arg(2));
	}

	/**
	 * helper for editing entities
	 * redirects to the provided callback. used for e.g. edit/ and editplain/
	 * @param String $model
	 * @param Integer $id
	 * @param String $callback
	 */
	private function editHelp($model, $id, $callback){
		if(false === $this->request->isPost()){
			$item = $this->model->findOne($model, $id);
			$this->load->set('model', $item);
			$this->load->set('modeltype', $model);
			return;
		}
		$update = new UpdateObject($model, $id);
		$update->setData($this->dataObject->updatedata);
		$update->update();
		$this->registerRedirect('/admin/'.$callback.'/'.$model.'/'.$id);
	}

	/**
	 * edits an entity, frontend uses tinymce editor, needs special route
	 * but as we have a helper function, the code for updating is the same
	 */
	function editAction(){
		$this->editHelp(func_get_arg(2), func_get_arg(3), 'edit');
	}

	/**
	 * edits an entity, frontend don't uses tinymce editor, needs special route
	 * but as we have a helper function, the code for updating is the same
	 */
	function editplainAction(){
		$this->editHelp(func_get_arg(2), func_get_arg(3), 'editplain');
	}

	/**
	 * deletes an entity
	 */
	function deleteAction(){
		$delete = new DeleteObject(func_get_arg(2), func_get_arg(3));
		$delete->delete();
		$this->registerRedirect('', Redirect::$historyBack);
	}

	/**
	 * adds an entity
	 */
	function addAction(){
		if(false === $this->request->isPost()){
			$this->load->set('model', func_get_arg(2));
			$this->load->set('columns', DatabaseInformation::getColumnsForTable(DatabaseManager::getInstance()->getConnection()->getDatabase(), func_get_arg(2)));
			$this->load->set('data', $this->dataObject->getRawPOST());
			return;
		}
		$save = new SaveObject(func_get_arg(2));
		$save->setData($this->dataObject->adddata);
		$saveResult = $save->save();
		if(true === $saveResult->queryWasSuccessfull()){
			Helper::addSplash('Entry saved');
			return $this->registerRedirect('/admin/listing/'.func_get_arg(2));
		}
		Helper::addSplash('Entry could <b>NOT</b> be saved!');
	}
}