<?
/**
 * controller for the cms and static content stuff like errors, impress, contact, etc
 *
 * @author wolxXx
 * @version 1.0
 * @package application
 * @subpackage controllers
 *
 */
class CmsController extends CoreController{
	function setActionAndView(){
		if('' === func_get_arg(0)){
			$this->action = 'index';
			$this->view = 'index';
			return;
		}

		if(true === method_exists($this, func_get_arg(0).'Action')){
			$this->action = func_get_arg(0);
			$this->view = func_get_arg(0);
			return;
		}
		if($this->load->viewExists(func_get_arg(0))){
			$this->action = 'noop';
			$this->view = func_get_arg(0);
			return;
		}

		if(null === $this->model->getContent(func_get_arg(0))){
			throw new NoViewException();
		}
		$this->action = 'cms';
		$this->view = 'cms';
	}

	/**
	 * just displays an item from the database table cms
	 * @throws NoViewException
	 */
	function cmsAction(){
		$cmsContent = $this->model->getContent(func_get_arg(0));
		if('0' === $cmsContent->is_active && false === Auth::hasAccess(USER_TYPE_ADMIN)){
			throw new NoViewException();
		}
		$this->load->set('entry', $cmsContent);
	}

	/**
	 * main starting index function
	 */
	function indexAction(){}


	/**
	 * the error pages
	 */
	function errorAction(){
		if(null !== $this->stack->get('last_error')){
			$this->load->set('last_error', $this->stack->get('last_error'));
			$this->stack->set('last_error', null);
		}else{
			$this->load->set('last_error', null);
		}

		$this->load->set('type', func_get_arg(1));
		if(true === in_array($this->load->get('type'), array('404', 'no_view'))){
			header("HTTP/1.0 404 Not Found");
		}elseif(true === in_array($this->load->get('type'), array('403', 'no_auth', 'pending', 'banned'))){
			header("HTTP/1.0 403 Access Denied");
		}elseif(true === in_array($this->load->get('type'), array('500', 'app'))){
			header("HTTP/1.0 500 Internal Server Error");
		}
	}
}