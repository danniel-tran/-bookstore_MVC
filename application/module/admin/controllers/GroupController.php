<?php
class GroupController extends Controller{
	
	public function __construct($arrParams){
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('admin/main/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}
	
	// ACTION: LIST GROUP
	public function indexAction(){
		$this->_view->_title 		= 'Group Manager :: List';
		$totalItems					= $this->_model->countItem($this->_arrParam, null);
		
		$configPagination = array('totalItemsPerPage'	=> 5, 'pageRange' => 3);
		$this->setPagination($configPagination);
		$this->_view->pagination	= new Pagination($totalItems, $this->_pagination);
		$this->_view->Items 		= $this->_model->listItem($this->_arrParam, null);
		$this->_view->render('group/index');
	}
	
	// ACTION: ADD & EDIT GROUP

	public function formAction(){
		$this->_view->_title = 'Group Manager :: Add';
		
		if(isset($this->_arrParam['id'])){
			$this->_view->_title = 'Group Manager :: Edit';
			$this->_arrParam['form'] = $this->_model->infoItem($this->_arrParam);
			if(empty($this->_arrParam['form'])) URL::redirect('admin', 'group', 'index');
		}
		
		if($this->_arrParam['form']['token'] > 0){

			$validate = new Validate($this->_arrParam['form']);
			$validate->addRule('name', 'string', array('min' => 3, 'max' => 255))
					 ->addRule('ordering', 'int', array('min' => 1, 'max' => 100))
					 ->addRule('status', 'status', array('deny' => array('default')))
					 ->addRule('group_acp', 'status', array('deny' => array('default')))
					 ->addRule('picture', 'file', array('min' => 1, 'max' => 1000000, 'entension' => array('jpg', 'png')), false);
			$validate->run();
			$this->_arrParam['form'] = $validate->getResult();
			if($validate->isValid() == false){
				$this->_view->errors = $validate->showErrors();
			}else{
				$task	= (isset($this->_arrParam['form']['id'])) ? 'edit' : 'add';
				$id	= $this->_model->saveItem($this->_arrParam, array('task' => $task));
				if($this->_arrParam['type'] == 'save-close') 	URL::redirect('admin', 'group', 'index');
				if($this->_arrParam['type'] == 'save-new') 		URL::redirect('admin', 'group', 'form');
				if($this->_arrParam['type'] == 'save') 			URL::redirect('admin', 'group', 'form', array('id' => $id));
			}
		}
		
		$this->_view->arrParam = $this->_arrParam;
		$this->_view->render('group/form');
	}

	// ACTION: AJAX STATUS (*)
	public function ajaxStatusAction(){
		$result = $this->_model->changeStatus($this->_arrParam, array('task' => 'change-ajax-status'));
		echo json_encode($result);
	}
	
	// ACTION: AJAX GROUP ACP (*)
	public function ajaxACPAction(){
		$result = $this->_model->changeStatus($this->_arrParam, array('task' => 'change-ajax-group-acp'));
		echo json_encode($result);
	}
	
	// ACTION: STATUS (*)
	public function statusAction(){
		$this->_model->changeStatus($this->_arrParam, array('task' => 'change-status'));
		URL::redirect('admin', 'group', 'index');
	}
	
	// ACTION: TRASH (*)
	/*
	public function trashAction(){
		$this->_model->deleteItem($this->_arrParam);
		URL::redirect('admin', 'group', 'index');
	}
	*/
	
	// ACTION: ORDERING (*)
	public function orderingAction(){
		$this->_model->ordering($this->_arrParam);
		URL::redirect('admin', 'group', 'index');
	}
}