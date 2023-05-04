<?php
class Template
{


	private $_fileConfig;		// File config (admin/main/template.ini)
	private $_fileTemplate;		// File template (admin/main/inde.php)
	private $_folderTemplate;	// Folder template (admin/main/)
	private $_controller;		// Controller Object

	//===== CONSTRUCT ======
	public function __construct($controller)
	{
		$this->_controller = $controller;
	}

	//===== LOAD ======
	public function load()
	{
		$fileConfig 	= $this->getFileConfig();
		$folderTemplate = $this->getFolderTemplate();
		$fileTemplate 	= $this->getFileTemplate();

		$pathFileConfig	= PATH_TEMPLATE . $folderTemplate . $fileConfig;
		if (file_exists($pathFileConfig)) {
			$arrCongif = parse_ini_file($pathFileConfig);


			$view = $this->_controller->getView();
			$view->_title 		= $view->createTitle($arrCongif['title']);
			$view->_metaHTTP	= $view->createMeta($arrCongif['metaHTTP'], 'http-equiv');
			$view->_metaName 	= $view->createMeta($arrCongif['metaName'], 'name');
			$view->_jsFilesURL 	= $view->createURLJS($arrCongif["urlscript"]);
			$view->_cssFiles 	= $view->createLink($this->_folderTemplate . $arrCongif['dirCss'], $arrCongif['fileCss'], 'css');
			$view->_jsFiles 	.= $view->createLink($this->_folderTemplate . $arrCongif['dirJs'], $arrCongif['fileJs'], 'js');
			$view->_dirImg 		= URL_TEMPLATE . $this->_folderTemplate . $arrCongif['dirImg'];

			$view->setTemplatePath(PATH_TEMPLATE . $folderTemplate . $fileTemplate);
		}
	}

	// SET FILE TEMPLATE ('INDEX.PHP')
	public function setFileTemplate($value = 'index.php')
	{
		$this->_fileTemplate = $value;
	}

	// GET FILE TEMPLATE
	public function getFileTemplate()
	{
		return $this->_fileTemplate;
	}

	// SET FILE CONFIG ('TEMPLATE.INI)
	public function setFileConfig($value = 'template.ini')
	{
		$this->_fileConfig = $value;
	}

	// GET FILE CONFIG
	public function getFileConfig()
	{
		return $this->_fileConfig;
	}

	// SET FOLDER TEMPLATE (DEFAULT/MAIN/)
	public function setFolderTemplate($value = 'default/main/')
	{
		$this->_folderTemplate = $value;
	}

	// GET FOLDER CONFIG
	public function getFolderTemplate()
	{
		return $this->_folderTemplate;
	}
}
