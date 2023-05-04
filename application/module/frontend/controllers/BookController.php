<?php
class BookController extends Controller
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

	//===== LIST ======
	public function listAction()
	{
		$this->_view->_title 		= 'Sách';
		$totalItems          = $this->_model->countItems($this->_arrParam);
		$configPagination    = ['totalItemsPerPage'    => 5, 'pageRange' => 3];
		$this->setPagination($configPagination);
		$this->_view->pagination    = new Pagination($totalItems, $this->_pagination);
		$this->_view->category       = $this->_model->listItem($this->_arrParam, ['task' => 'category']);
		$this->_view->allBook        = $this->_model->listItem($this->_arrParam, ['task' => null]);
		$this->_view->bookInCategory = $this->_model->listItem($this->_arrParam, ['task' => 'bookInCategory']);
		$this->_view->specialBook1   = $this->_model->listItem($this->_arrParam, ['task' => 'specialBook1']);
		$this->_view->specialBook2   = $this->_model->listItem($this->_arrParam, ['task' => 'specialBook2']);
		$this->_view->render($this->_arrParam['controller'] . '/list');
	}

	// // ACTION: DETAIL BOOK
	public function detailAction()
	{
		$this->_view->_title 		= 'Info book';
		$ids = $this->_model->getIDBook($this->_arrParam);
		if (isset($this->_arrParam['book_id']) && $ids == true) {
			$this->_view->relateBook   = $this->_model->listItem($this->_arrParam, ['task' => 'relateBook']);
			$this->_view->infoBook     = $this->_model->infoItem($this->_arrParam);
			$this->_view->specialBook2 = $this->_model->listItem($this->_arrParam, ['task' => 'specialBook2']);
			$this->_view->specialBook1 = $this->_model->listItem($this->_arrParam, ['task' => 'specialBook1']);
			$this->_view->newBook1     = $this->_model->listItem($this->_arrParam, ['task' => 'newBook1']);
			$this->_view->newBook2     = $this->_model->listItem($this->_arrParam, ['task' => 'newBook2']);
			$this->_view->render($this->_arrParam['controller'] . '/detail');
		} else {
			URL::redirect('frontend', 'index', 'index');
		}
	}

	// // ACTION: RELATE BOOK
	public function relateAction()
	{
		$this->_view->bookRelate	= $this->_model->listItem($this->_arrParam, array('task' => 'books-relate'));
		$this->_view->render('book/relate', false);
	}


	public function thumbnail3Action()
	{
		// $this->_view->_title 		= 'Info book'	;
		$ids = $this->_model->getIDBook($this->_arrParam);
		if (isset($this->_arrParam['book_id']) && $ids == true) {
			$this->_view->relateBook   = $this->_model->listItem($this->_arrParam, ['task' => 'relateBook']);
			$this->_view->infoBook     = $this->_model->infoItem($this->_arrParam);
			$this->_view->specialBook2 = $this->_model->listItem($this->_arrParam, ['task' => 'specialBook2']);
			$this->_view->specialBook1 = $this->_model->listItem($this->_arrParam, ['task' => 'specialBook1']);
			$this->_view->newBook1     = $this->_model->listItem($this->_arrParam, ['task' => 'newBook1']);
			$this->_view->newBook2     = $this->_model->listItem($this->_arrParam, ['task' => 'newBook2']);
			$this->_view->render($this->_arrParam['controller'] . '/test-thumbnail-3');
		} else {
			URL::redirect('frontend', 'index', 'index');
		}
	}
}
