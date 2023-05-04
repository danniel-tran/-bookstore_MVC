<?php
class IndexController extends Controller{
	
	//===== __CONSTRUCT ======
	public function __construct($arrParams){
		parent::__construct($arrParams);
	}
	
	//===== LOGIN ======
	public function loginAction(){
		$userInfo	= Session::get('user');
		if($userInfo['login'] == true && $userInfo['time'] + TIME_LOGIN >= time()){
			URL::redirect('admin', 'index', 'index');
		}
		
		$this->_templateObj->setFolderTemplate('admin/main/');
		$this->_templateObj->setFileTemplate('login.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
		
		$this->_view->_title 		= 'Login';
		
		if($this->_arrParam['form']['token'] > 0){
			$validate	= new Validate($this->_arrParam['form']);
			$username	= $this->_arrParam['form']['username'];
			$password	= md5($this->_arrParam['form']['password']);
			$query		= "SELECT `id` FROM `user` WHERE `username` = '$username' AND `password` = '$password'";
			$validate->addRule('username', 'existRecord', array('database' => $this->_model, 'query' => $query));
			$validate->run();
			
			if($validate->isValid()==true){
				$infoUser		= $this->_model->infoItem($this->_arrParam);
				$arraySession	= array(
										'login'		=> true,
										'info'		=> $infoUser,
										'time'		=> time(),
										'group_acp'	=> $infoUser['group_acp']
									);
				Session::set('user', $arraySession);
				URL::redirect('admin', 'index', 'index');
			}else{
				$this->_view->errors	= $validate->showErrors();
			}
		}
		
		$this->_view->render('index/login');
	}
	
	//===== INDEX ======
	public function indexAction(){
		
		$this->_templateObj->setFolderTemplate('admin/theme_admin/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');	
		$this->_templateObj->load();
		$this->_view->_title 		= 'Index';
		
		$this->_view->render('index/index');
	}
	
	//===== PROFILE ======
	public function profileAction(){
		$this->_templateObj->setFolderTemplate('admin/main/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	
		$this->_view->_title 		= 'Profile';
		$userObj	= Session::get('user');

		$this->_view->arrParam['form']	= $userObj['info'];
		$this->_view->render('index/profile');
	}
	
	//===== LOGOUT ======
	public function logoutAction(){
		Session::delete('user');
		URL::redirect('admin', 'index', 'login');
	}
}