<?php
class View
{

	public $_moduleName;
	public $_templatePath;
	public $_title;
	public $_metaHTTP;
	public $_metaName;
	public $_cssFiles;
	public $_jsFiles;
	public $_dirImg;
	public $_fileView;

	//===== CONSTRUCT ======
	public function __construct($moduleName)
	{
		$this->_moduleName = $moduleName;
	}

	//===== RENDER ======
	public function render($fileInclude, $loadFull = true)
	{
		$path = PATH_MODULE . $this->_moduleName . DS . 'views' . DS . $fileInclude . '.php';
		if (file_exists($path)) {

			if ($loadFull == true) {
				$this->_fileView = $fileInclude;
				require_once $this->_templatePath;
			} else {
				require_once $path;
			}
		} else {
			echo '<h3>' . __METHOD__ . ': Error</h3>';
		}
	}

	// THIẾT LẬP ĐƯỜNG DẪN ĐẾN TEMPLATE
	public function setTemplatePath($path)
	{
		$this->_templatePath = $path;
	}

	// CREATE CSS - JS
	public function createLink($path, $files, $type = 'css')
	{
		$xhtml = '';
		if (!empty($files)) {
			$path = URL_TEMPLATE . $path . DS;
			foreach ($files as $file) {
				if ($type == 'css') {
					$xhtml .= '<link rel="stylesheet" type="text/css" href="' . $path . $file . '"/>';
				} else if ($type == 'js') {
					$xhtml .= '<script type="text/javascript" src="' . $path . $file . '"></script>';
				}
			}
		}
		return $xhtml;
	}

	// CREATE META (NAME - HTTP)
	public function createMeta($arrMeta, $typeMeta = 'name')
	{
		$xhtml = '';
		if (!empty($arrMeta)) {
			foreach ($arrMeta as $meta) {
				$temp = explode('|', $meta);
				$xhtml .= '<meta ' . $typeMeta . '="' . $temp[0] . '" content="' . $temp[1] . '" />';
			}
		}
		return $xhtml;
	}

	// CREATE TITLE
	public function createTitle($value)
	{
		return '<title>' . $value . '</title>';
	}

	// SET TITLE
	public function setTitle($value)
	{
		$this->_title = '<title>' . $value . '</title>';
	}

	// SET CSS
	public function appendCSS($arrayCSS)
	{
		if (!empty($arrayCSS)) {
			foreach ($arrayCSS as $css) {
				$file = URL_APPLICATION . $this->_moduleName . DS . 'views' . DS . $css;
				$this->_cssFiles .= '<link rel="stylesheet" type="text/css" href="' . $file . '"/>';
			}
		}
	}

	// SET JS
	public function appendJS($arrayJS)
	{
		if (!empty($arrayJS)) {
			foreach ($arrayJS as $js) {
				$file = URL_APPLICATION . $this->_moduleName . DS . 'views' . DS . $js;
				$this->_jsFiles .= '<script type="text/javascript" src="' . $file . '"></script>';
			}
		}
	}

	//URL JS LINK
	public function  createURLJS($arrConfig)
	{
		if (!empty($arrConfig)) {
			foreach ($arrConfig as $jsLink) {
				$this->_jsFiles .= '<script type="text/javascript" src="' . $jsLink . '"></script>';
			}
		}
	}
}
