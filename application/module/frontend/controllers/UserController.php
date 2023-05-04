<?php
class UserController extends Controller
{

	//===== CONSTRUCT ======
	public function __construct($arrParams)
	{
		parent::__construct($arrParams);
		$this->_templateObj->setFolderTemplate('default/main/');
		$this->_templateObj->setFileTemplate('index.php');
		$this->_templateObj->setFileConfig('template.ini');
		$this->_templateObj->load();
	}

	//===== ĐĂNG KÍ ======
	public function registerAction()
	{

		// $userInfo    = Session::get('user');
		// if ($logged = ($userInfo['login'] == true && $userInfo['time'] + TIME_LOGIN >= time())) {			
		// 	URL::redirect('frontend', 'user', 'index');
		// };


		if (isset($this->_arrParam['form'])) {
			URL::checkRefreshPage($this->_arrParam['form']['token'], 'frontend', 'user', 'register');
			if (isset($this->_arrParam['form']['token'])) {
				$this->_validate->validate($this->_model);
				$this->_arrParam['form'] = $this->_validate->getResult();
				if ($this->_validate->isValid()) {
					$this->_view->errors = $this->_validate->showErrorsAdmin();
				} else {
					$id = $this->_model->saveItem($this->_arrParam, ['task' => 'user_register']);
					URL::redirect('frontend', 'index', 'notice', ['type' => 'register-success']);
				}
			}
			$this->_view->arrParam = $this->_arrParam;
		}

		$this->_view->render('user/register');
	}

	//===== ĐĂNG NHẬP  ======
	public function loginAction()
	{
		$this->_view->_title = 'Đăng nhập';
		$userInfo    = Session::get('user');
		if ($logged = ($userInfo['login'] == true && $userInfo['time'] + TIME_LOGIN >= time())) {
			Session::destroy();
			URL::redirect('frontend', 'user', 'index');
		};

		if ($this->_arrParam['form']['token'] > 0) {
			$this->_validate->validateFrontend($this->_model);
			$this->_arrParam['form'] = $this->_validate->getResult();
			if (!$this->_validate->isValid()) {
				$this->_view->errors = $this->_validate->showErrorsAdmin();
			} else {
				// $task = isset($this->_arrParam['form']['id']) ? 'edit' : 'add';
				// $id = $this->_model->saveItem($this->_arrParam, ['task' => $task]);
				// $this->redirectAfterSave(['id' => $id]);
				$infoUser = $this->_model->infoItem($this->_arrParam['form']);
				$arrSessions = ['login' => true, 'info' => $infoUser, 'time' => time(), 'group_acp' => $infoUser['group_acp']];
				Session::set('user', $arrSessions);
				URL::redirect('frontend', 'index', 'index');
			}
		}
		$this->_view->render('user/login');
	}

	//ORDER
	public function orderAction()
	{
		$cart = Session::get('cart');
		$arrParamsLink = array(
			'book_id' => $this->_arrParam['book_id'],
			'price' => $this->_arrParam['price']
		);
		if (empty($cart)) {
			$cart = [];
			$cart['quantity'] = [];
			$cart['price'] = [];
			$cart['quantity'][$arrParamsLink['book_id']] = $this->_arrParam['quantityOrder'];
			$cart['price'][$arrParamsLink['book_id']] = $arrParamsLink['price'];
		} else {
			if (key_exists($arrParamsLink['book_id'], $cart['quantity'])) {
				$cart['quantity'][$arrParamsLink['book_id']] += $this->_arrParam['quantityOrder'];
				$cart['price'][$arrParamsLink['book_id']] = $arrParamsLink['price'] * $cart['quantity'][$arrParamsLink['book_id']];
			} else {
				$cart['quantity'][$arrParamsLink['book_id']] = $this->_arrParam['quantityOrder'];
				$cart['price'][$arrParamsLink['book_id']] = $arrParamsLink['price'] * $cart['quantity'][$arrParamsLink['book_id']];
			};
		};
		Session::set('cart', $cart);
		$result =  Session::get('cart');
		echo json_encode($result);
	}

	//Cart
	public function cartAction()
	{
		$this->_view->tittle = 'Cart';
		$this->_view->bookOrder   = $this->_model->listCart($this->_arrParam);
		$this->_view->render('user/cart');
	}

	//Buy
	public function buyAction()
	{
		$this->_model->saveItem($this->_arrParam, ['task' => 'book-in-cart']);
		URL::redirect("frontend", "book", "list");
	}

	//Account
	public function accountAction()
	{
		$this->_view->render('user/account');
	}
	//History
	public function historyAction()
	{
		$this->_view->historyItem = $this->_model->listHistory($this->_arrParam);
		$this->_view->render('user/history');
	}
}
