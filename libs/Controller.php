<?php
class Controller
{

	protected $_view;  				// VIEW OBJECT
	protected $_model; 				// MODEL OBJECT
	protected $_validate; 			// VALIDATE OBJECT
	protected $_module;			// MODULE
	protected $_controller;			// CONTROLLER

	protected $_templateObj;		// TEMPLATE OBJECT
	protected $_arrParam;			// PARAMS (GET - POST)
	protected $_pagination	= array( // PAGINATION
		'totalItemsPerPage'	=> 3,
		'pageRange'			=> 2,
	);

	//===== CONSTRUCT ======							
	public function __construct($arrParams)
	{
		$this->_module     = $arrParams['module'];
		$this->_controller = $arrParams['controller'];

		$this->_pagination['currentPage']	= (isset($arrParams['page'])) ? $arrParams['page'] : 1;
		$arrParams['pagination'] = $this->_pagination;
		$this->setParams($arrParams);

		$this->setModel($arrParams['module'], $arrParams['controller']);
		$this->setValidate($arrParams['module'], $arrParams['controller']);
		$this->setTemplate();
		$this->setView($arrParams['module']);
		$this->_view->arrParam = $arrParams;

		// echo '<pre>';
		// print_r($this->_templateObj->_controller);
		// echo '</pre>';
	}

	// SET VALIDATE
	public function setValidate($moduleName, $controllerName)
	{
		$validateName = ucfirst($controllerName) . 'Validate';
		$path         = PATH_MODULE . $moduleName . DS . 'validates' .  DS . $validateName . '.php';
		if (file_exists($path)) {
			require_once $path;
			$this->_validate	= new $validateName($this->_arrParam);
		}
	}

	// SET MODEL
	public function setModel($moduleName, $modelName)
	{
		$modelName = ucfirst($modelName) . 'Model';
		$path = PATH_MODULE . $moduleName . DS . 'models' .  DS . $modelName . '.php';
		if (file_exists($path)) {
			require_once $path;
			$this->_model	= new $modelName();
		}
	}

	// GET MODEL
	public function getModel()
	{
		return $this->_model;
	}

	// SET VIEW
	public function setView($moduleName)
	{
		$this->_view = new View($moduleName);
	}

	// GET VIEW
	public function getView()
	{
		return $this->_view;
	}

	// SET TEMPLATE
	public function setTemplate()
	{
		$this->_templateObj = new Template($this);
	}

	// GET TEMPLATE
	public function getTemplate()
	{
		return $this->_templateObj;
	}

	// GET PARAMS
	public function setParams($arrParam)
	{
		$this->_arrParam = $arrParam;
	}

	// SET PARAMS
	public function getParams($arrParam)
	{
		$this->_arrParam = $arrParam;
	}

	// SET PAGINATION
	public function setPagination($config)
	{
		$this->_pagination['totalItemsPerPage'] = $config['totalItemsPerPage'];
		$this->_pagination['pageRange']			= $config['pageRange'];

		$this->_arrParam['pagination']			= $this->_pagination;
		$this->_view->arrParam					= $this->_arrParam;
	}

	//===== REDIRECT AFTER SAVE ======
	public function redirectAfterSave($params)
	{
		if ($this->_arrParam['type'] == 'save-close')   URL::redirect($this->_module, $this->_controller, 'index');
		if ($this->_arrParam['type'] == 'save-new')     URL::redirect($this->_module, $this->_controller, 'form');
		if ($this->_arrParam['type'] == 'save')         URL::redirect($this->_module, $this->_controller, 'form', ['id' => $params['id']]);
	}
}
