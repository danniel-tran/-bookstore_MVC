<?php
class CategoryController extends Controller{
	
	//===== __CONSTRUCT ======
	public function __construct($arrParams){
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('default/main/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}
	
	// ACTION: LIST CATGORIES
	public function indexAction(){
		require_once PATH_LIBRARY_EXT . 'XML.php';
		$this->_view->_title 		= 'Category List';
		$this->_view->Items 		= XML::getContentXML('categories.xml');
		$this->_view->render('category/index');
	}
	
}