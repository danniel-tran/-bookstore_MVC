<?php
class IndexController extends Controller
{

	//===== __CONSTRUCT ======
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('default/main/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	//===== INDEX ======
	public function indexAction()
	{

		$this->_view->_title          = 'Trang chủ';
		$this->_view->slider          = $this->_model->listSlider($this->_arrParam);
		$this->_view->Items           = $this->_model->listItems($this->_arrParam);
		$this->_view->Items1          = $this->_model->listDanhMucNoiBat($this->_arrParam);
		$this->_view->danhsach        = $this->_model->layid($this->_arrParam);
		$this->_view->specialBook     = $this->_model->listItem($this->_arrParam, ['task' => 'specialBook']);

		$this->_view->specialCategory = $this->_model->listItem($this->_arrParam, ['task' => 'specialCategory']);
		$this->_view->bookInCategory  = $this->_model->listItem($this->_arrParam, ['task' => 'bookInCategory']);

		$this->_view->render('index/index');
	}

	//===== AJAX QUICK VIEW ACTION ======
	public function ajaxQuickViewAction()
	{
		$result = $this->_model->infoItem($this->_arrParam, ['task' => 'quick-view']);
		echo json_encode($result);
	}

	//===== NOTICE ======
	public function noticeAction()
	{
		$this->_view->render('index/notice');
	}



	//===== LOGOUT ======
	public function logoutAction()
	{
		Session::delete('user');
		URL::redirect('frontend', 'index', 'index');
	}
}
