<?php
class CategoryController extends Controller
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

	// ACTION: LIST CATGORIES
	public function indexAction()
	{
		
		$this->_view->_title = 'Danh mục';
		$totalItems          = $this->_model->countItems($this->_arrParam);
		$configPagination    = ['totalItemsPerPage'    => 5, 'pageRange' => 3];
        $this->setPagination($configPagination);
        $this->_view->pagination    = new Pagination($totalItems, $this->_pagination);
		$this->_view->Items = $this->_model->listItem($this->_arrParam);
		$this->_view->render('category/index');
	}
}
