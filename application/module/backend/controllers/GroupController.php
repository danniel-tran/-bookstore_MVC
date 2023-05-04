<?php
class GroupController extends Controller
{

	//===== CONSTRUCT ======
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('admin/theme_admin/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	//===== INDEX (LIST) ======
	public function indexAction()
	{
		$this->_view->_title = ucfirst($this->_controller) . ' Manager :: List';

		$totalItems          = $this->_model->countItems($this->_arrParam);
		$configPagination    = ['totalItemsPerPage' => 5, 'pageRange' => 3];
		$this->setPagination($configPagination);
		$this->_view->pagination    = new Pagination($totalItems, $this->_pagination);

		$this->_view->countActive   = $this->_model->countItems($this->_arrParam, ['task' => 'count-active']);
		$this->_view->countInactive = $this->_model->countItems($this->_arrParam, ['task' => 'count-inactive']);
		$this->_view->Items         = $this->_model->listItem($this->_arrParam, null);
		$this->_view->render($this->_controller . '/index');
	}

	//===== ADD EDIT ======
	public function formAction()
	{
		$this->_view->_title 		= ucfirst($this->_controller) . ' Manager :: Add';
		if (isset($this->_arrParam['id'])) {
			$this->_view->_title 		= ucfirst($this->_controller) . ' Manager :: Edit';
			$this->_arrParam['form'] 	= $this->_model->infoItem($this->_arrParam);
			if (empty($this->_arrParam['form'])) URL::redirect($this->_module, $this->_controller, 'index');
		}

		if ($this->_arrParam['form']['token'] > 0) {

			$this->_validate->validate();

			$this->_arrParam['form'] = $this->_validate->getResult();
			if ($this->_validate->isValid() == false) {
				$this->_view->errors = $this->_validate->showErrorsAdmin();
			} else {
				$task = (isset($this->_arrParam['form']['id'])) ? 'edit' : 'add';
				$id = $this->_model->saveItem($this->_arrParam, ['task' => $task]);
				if ($this->_arrParam['type'] == 'save-close') URL::redirect($this->_module, $this->_controller, 'index');
				if ($this->_arrParam['type'] == 'save-new') URL::redirect($this->_module, $this->_controller, 'form');
				if ($this->_arrParam['type'] == 'save') URL::redirect($this->_module, $this->_controller, 'form', ['id' => $id]);
			}
		}
		$this->_view->arrParam = $this->_arrParam;
		$this->_view->render($this->_controller . '/form');
	}


	//===== AJAX STATUS ======
	public function ajaxStatusAction()
	{
		$result = $this->_model->ajaxStatus($this->_arrParam);
		echo json_encode($result);
	}

	//===== AJAX GROUP ACP  ======
	public function ajaxGroupACPAction()
	{
		$result = $this->_model->ajaxGroupACP($this->_arrParam);
		echo json_encode($result);
	}

	//===== AJAX ORDERING ACTION ======
	public function ajaxOrderingAction()
	{
		$result = $this->_model->ajaxOrdering($this->_arrParam);
		echo json_encode($result);
	}

	//===== DELETE ACTION ======
	public function deleteAction()
	{
		$this->_model->deleteItems($this->_arrParam);
		URL::redirect($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
	}

	//===== ACTIVE ACTION ======
	public function activeAction()
	{
		$this->_model->changeStatus($this->_arrParam, ['task' => 'active']);
		URL::redirect($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
	}

	//===== INACTIVE ACTION ======
	public function inactiveAction()
	{
		$this->_model->changeStatus($this->_arrParam, ['task' => 'inactive']);
		URL::redirect($this->_arrParam['module'], $this->_arrParam['controller'], 'index');
	}
}
