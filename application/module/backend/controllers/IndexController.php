
<?php
class IndexController extends Controller
{

	//===== __CONSTRUCT ======
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('admin/theme_admin/');
		$this->_templateObj->setFileTemplate('login.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	//===== LOGIN ======
	public function loginAction()
	{

		$userInfo	= Session::get('user');
		if ($userInfo['login'] == true && $userInfo['time'] + TIME_LOGIN >= time()) {
			URL::redirect('backend', 'index', 'index');
		}

		$this->_view->_title 		= 'Login';
		if ($this->_arrParam['form']['token'] > 0) {

			$this->_validate->validate($this->_model);

			$this->_arrParam['form'] = $this->_validate->getResult();
			if ($this->_validate->isValid() == true) {
				$infoUser		= $this->_model->infoItem($this->_arrParam);
				$arraySession	= ['login' => true, 'info' => $infoUser, 'time' => time(), 'group_acp'	=> $infoUser['group_acp']];
				Session::set('user', $arraySession);
				URL::redirect('backend', 'index', 'index');
			} else {
				$this->_view->errors	= $this->_validate->showErrorsAdmin();
			}
		}
		$this->_view->render('index/login');
	}


	//===== INDEX ACTION ======
	public function indexAction()
	{
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->load();

		$this->_view->_title        = 'Dashboard';
		$this->_view->countGroup    = $this->_model->countItems($this->_arrParam, ['task' => 'group']);
		$this->_view->countUser     = $this->_model->countItems($this->_arrParam, ['task' => 'user']);
		$this->_view->countCategory = $this->_model->countItems($this->_arrParam, ['task' => 'category']);
		$this->_view->countBook     = $this->_model->countItems($this->_arrParam, ['task' => 'book']);
		$this->_view->countSlider   = $this->_model->countItems($this->_arrParam, ['task' => 'slider']);
		$this->_view->render($this->_controller . '/index');
	}

	// ===== PROFILE======
	public function profileAction()
	{

		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->load();
		$this->_view->_title 		= 'Profile';

		if ($this->_arrParam['form']['token'] > 0) {

			$this->_validate->validateProfile($this->_model);

			$this->_arrParam['form'] = $this->_validate->getResult();
			if ($this->_validate->isValid() == false) {
				$this->_view->errors = $this->_validate->showErrorsAdmin();
			} else {

				$data = $this->_model->saveItem($this->_arrParam);
				$_SESSION['user']['info']['fullname'] = $data['fullname'];
				$_SESSION['user']['info']['email'] = $data['email'];

				if ($this->_arrParam['type'] == 'save-close') URL::redirect($this->_module, $this->_controller, 'index');
				if ($this->_arrParam['type'] == 'save-new') URL::redirect($this->_module, $this->_controller, 'profile');
				if ($this->_arrParam['type'] == 'save') URL::redirect($this->_module, $this->_controller, 'profile');
			}
		}

		$userObj	= Session::get('user');

		$this->_view->arrParam['form']	= $userObj['info'];
		$this->_view->render('index/profile');
	}

	//===== LOGOUT ======
	public function logoutAction()
	{
		Session::delete('user');
		URL::redirect('backend', 'index', 'login');
	}
}
