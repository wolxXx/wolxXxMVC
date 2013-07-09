<?
/**
 * container for form elements
 *
 * @author wolxXx
 * @version 0.3
 * @package wolxXxMVC
 * @subpackage HTML
 */
class Form extends ContainableDomElementAbstract{
	/**
	 * flag if the enctype is multipart/form-data
	 *
	 * @var boolean
	 */
	protected $isUploadForm = false;

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::getDefaultConf()
	 */
	public static function getDefaultConf(){
		return array(
			'action' => '',
			'method' => 'post',
			'enctype' => null
		);
	}

	/**
	 * setter for the method
	 * only post or get are allowed!
	 *
	 * @param string $method
	 * @return Form
	 */
	public function setMethod($method){
		$this->data->set('method', 'post' === $method? 'post' : 'get');
		return $this;
	}

	/**
	 * setter for the action
	 *
	 * @param string $action
	 * @return Form
	 */
	public function setAction($action){
		$this->data->set('action', $action);
		return $this;
	}

	/**
	 * setter for upload form flag
	 *
	 * @param boolean $isUploadForm
	 * @return Form
	 */
	public function setIsUploadForm($isUploadForm = true){
		$this->isUploadForm = $isUploadForm;
		return $this;
	}

	/**
	 * (non-PHPdoc)
	 * @see DomElementInterface::display()
	 */
	public function display(){
		$this->displayLabelBefore();
		$conf = $this->data->getData();
		if(true === $this->isUploadForm){
			$conf['enctype'] = 'multipart/form-data';
			$conf['method'] = 'post';
		}
		HTML::renderFormStart($conf);
		foreach($this->children as $current){
			$current->display();
		}
		HTML::renderFormClose();
		$this->displayLabelAfter();
		return $this;
	}
}