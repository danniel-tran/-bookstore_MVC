<?php
class UserModel extends Model
{
	private $_columns = ['id', 'username', 'email', 'fullname', 'password', 'created', 'created_by', 'modified', 'modified_by', 'register_date', 'register_ip', 'status', 'ordering', 'group_id'];

	//===== __CONSTRUCT ======
	public function __construct()
	{
		parent::__construct();
		$this->setTable(TBL_USER);
		$userObj 			= Session::get('user');
		$this->_userInfo 	= $userObj['info'];
	}


	//LIST ORDER 

	public function orderList($arrParam, $option = null)
	{
		$query[]	= "SELECT `id`, `name`, `description`, `price`, `special`, `sale_off`, `picture`, `created`, `created_by`, `modified`, `modified_by`, `status`, `ordering`, `category_id`";
		$query[]	= "FROM `book`";
		$cid = $this->createInSQL($arrParam);
		$query[]	= "WHERE `id` IN (" . $cid['cols'] . ")";
		$query		= implode(" ", $query);
		$result		= $this->fetchAll($query);
		return $result;
	}

	//===== LIST BOOKINFO ======

	public function bookInfo($arrParam, $option = null)
	{
		$query[]	= "SELECT `id`, `name`, `description`, `price`, `special`, `sale_off`, `picture`, `created`, `created_by`, `modified`, `modified_by`, `status`, `ordering`, `category_id`";
		$query[]	= "FROM `book`";
		$query[]	= "WHERE `id` = '" . $arrParam['book_id'] . "'";
		$query		= implode(" ", $query);
		$result		= $this->fetchRow($query);
		return $result;
	}


	//===== LIST ITEM ======
	public function listItem($arrParam, $option = null)
	{
		//===== VARIABLE ======
		$filter_search = $arrParam['filter_search'];
		$filter_group_id = $arrParam['filter_group_id'];

		//===== QUERY ======
		$query[]	= "SELECT `id`, `username`, `email`, `fullname`, `password`, `created`, `created_by`, `modified`, `modified_by`, `register_date`, `register_ip`, `status`, `ordering`, `group_id`";
		$query[]	= "FROM `$this->table`";
		$query[]	= "WHERE `id` >0";

		//===== STATUS ======
		if ($arrParam['status'] == 'active') {
			$query[]	= "AND `status`= 1";
		}
		if ($arrParam['status'] == 'inactive') {
			$query[]	= "AND `status`= 0";
		}

		//===== FILTER_GROUP_ID ======
		if (isset($arrParam['filter_group_id']) && $arrParam['filter_group_id'] != 'default') {
			$query[]	= "AND `group_id`= '$filter_group_id'";
		}

		//===== FILTER_SEARCH ======
		if (isset($arrParam['filter_search'])) {
			if ($arrParam['key'] == 'all') {

				$query[]	= "AND (`username` LIKE \"%$filter_search%\" OR
									 `id` LIKE \"%$filter_search%\" OR 
									 `email` LIKE \"%$filter_search%\" OR 
									 `fullname` LIKE \"%$filter_search%\") ";
			} else if ($arrParam['key'] == 'id') {

				$query[]	= "AND `id` LIKE \"%$filter_search%\"  ";
			} else if ($arrParam['key'] == 'info') {

				$query[]	= "AND (`username` LIKE \"%$filter_search%\" OR 
									`email` LIKE \"%$filter_search%\" OR 
									`fullname` LIKE \"%$filter_search%\" )";
			}
		}

		//===== FILTER_COLUMN ======
		if (!empty($arrParam['filter_column']) && !empty($arrParam['filter_column_dir'])) {
			$col		= $arrParam['filter_column'];
			$colDir 	= $arrParam['filter_column_dir'];
			$query[] 	= "ORDER BY `$col` $colDir";
		} else {
			$query[] = "ORDER BY `id` DESC";
		}

		//===== PAGINATION ======
		$pagination         = $arrParam['pagination'];
		$totalItemsPerPage  = $pagination['totalItemsPerPage'];
		if ($totalItemsPerPage > 0) {
			$position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
			$query[]    = "LIMIT $position, $totalItemsPerPage";
		}

		//===== QUERY ======
		$query		= implode(" ", $query);
		$result		= $this->fetchAll($query);
		return $result;
	}

	//LIST CART
	public function listCart($arrParam, $option = null)
	{
		$result = array();
		$cart = Session::get('cart');
		if (!empty($cart)) {
			$id = '(';
			foreach ($cart['quantity'] as $key => $value) {
				$id .= "'$key',";
			};
			$id .= "'0')";
			$query[] = "SELECT `id`, `name`, `price`, `special`, `sale_off`, `picture`";
			$query[] = "FROM `" . TBL_BOOK . "`";
			$query[] = "WHERE `status` = 1 AND `id` IN $id";
			$query = implode(" ", $query);
			$result = $this->fetchAll($query);
			foreach ($result as $key => $value) {
				$result[$key]["unit-price"] =  $cart["price"][$value['id']] / $cart["quantity"][$value['id']];
				$result[$key]["total"] =  $cart["price"][$value['id']];
				$result[$key]["quantity"] =  $cart["quantity"][$value['id']];
			};
		};
		return $result;
	}

	//LIST History
	public function listHistory($arrParam, $option = null)
	{
		$result = array();
		$userInfo = Session::get('user')['info'];
		$username = $userInfo['username'];

		$query[] = "SELECT `id`, `username`, `books`, `prices`, `quantities`, `names`, `pictures`, `status`, `date`";
		$query[] = "FROM `" . TBL_CART . "`";
		$query[] = "WHERE `status` = 0 AND `username` = '$username'";
		$query[] = "ORDER BY `date` ASC";
		$query = implode(" ", $query);
		$result = $this->fetchAll($query);
		return $result;
	}
	//===== COUNT ITEMS ======
	public function countItems($arrParam, $option = null)
	{
		//===== VARIABLE ======
		$filter_search = $arrParam['filter_search'];
		$filter_group_id = $arrParam['filter_group_id'];

		//===== QUERY ======
		$query[] = "SELECT COUNT(`id`) AS 'total'";
		$query[] = "FROM `$this->table`";
		$query[] = "WHERE `id`>0";

		//===== COUNT ======
		if ($option != null) {
			if ($option['task'] == 'count-active') {
				$query[] = "AND `status`= 1 ";
			}
			if ($option['task'] == 'count-inactive') {
				$query[] = "AND `status`= 0 ";
			}
		} else {
			if ($arrParam['status'] == 'active') {
				$query[]	= "AND `status`= 1";
			}
			if ($arrParam['status'] == 'inactive') {
				$query[]	= "AND `status`= 0";
			}
			if (isset($arrParam['filter_group_id']) && $arrParam['filter_group_id'] != 'default') {
				$query[]	= "AND `group_id`= '$filter_group_id'";
			}

			if (isset($arrParam['filter_search'])) {
				if ($arrParam['key'] == 'default') {
					$query[]	= "AND (`username` LIKE \"%$filter_search%\" OR `id` LIKE \"%$filter_search%\" OR `email` LIKE \"%$filter_search%\" OR `fullname` LIKE \"%$filter_search%\") ";
				} else if ($arrParam['key'] == 'id') {
					$query[]	= "AND `id` LIKE \"%$filter_search%\"  ";
				} else if ($arrParam['key'] == 'info') {

					$query[]	= "AND (`username` LIKE \"%$filter_search%\" OR `email` LIKE \"%$filter_search%\" OR `fullname` LIKE \"%$filter_search%\" )";
				}
			}

			if (!empty($arrParam['filter_column']) && !empty($arrParam['filter_column_dir'])) {
				$col		= $arrParam['filter_column'];
				$colDir 	= $arrParam['filter_column_dir'];
				$query[] 	= "ORDER BY `$col` $colDir";
			} else {
				$query[] = "ORDER BY `id` DESC";
			}
		}

		//===== QUERY ======
		$query		= implode(" ", $query);
		$result		= $this->fetchRow($query);
		return $result['total'];
	}

	//===== ITEM IN SELECT BOX ======
	public function itemInSelectBox($arrParam, $options = null)
	{
		$query  = "SELECT `id`, `name` FROM `" . TBL_GROUP . "`";
		$result = $this->fetchPairs($query);
		if ($options['task'] == 'add-default') {
			$result = ['default' => '- Select Group -'] + $result;
		}
		return $result;
	}

	//===== INFO ITEM ======
	public function infoItem($arrParam, $option = null)
	{
		if ($option == null) {
			$query[]    = "SELECT `t`.`id`, `t`.`username`, `t`.`email`, `t`.`fullname`, `t`.`password`, `t`.`created`, `t`.`created_by`, `t`.`modified`, `t`.`modified_by`, `t`.`register_date`, `t`.`register_ip`, `t`.`status`, `t`.`ordering`, `t`.`group_id`,`g`.`group_acp` AS `group_acp`";
			$query[]    = "FROM `$this->table` AS `t`, `group` AS `g`";
			$query[]    = "WHERE `t`.`email` = '" . $arrParam['email'] . "' AND `t`.`password` = '" . md5($arrParam['password']) . "'";
			echo $query      = implode(" ", $query);
			$result     = $this->fetchRow($query);
			return $result;
		}
	}

	//===== INFO USER ======
	public function infoUser($arrParam, $option = null)
	{
		if ($option == null) {
			$query[]    = "SELECT `id`, `username`, `email`, `fullname`, `password`, `created`, `created_by`, `modified`, `modified_by`, `register_date`, `register_ip`, `status`, `ordering`, `group_id`";
			$query[]    = "FROM `$this->table`";
			$query[]    = "WHERE `email` = '" . $arrParam['email'] . "'";
			$query[]    = "AND `password` = '" . $arrParam['password'] . "'";
			echo $query      = implode(" ", $query);
			$result     = $this->fetchRow($query);
			return $result;
		}
	}


	//===== SAVE ITEM ======
	public function saveItem($params, $options = [])
	{
		if ($options['task'] == 'user_register') {
			$params['form']['created']      = date(DB_DATETIME_FORMAT);
			$params['form']['password'] = md5($params['form']['password']);
			$params['form']['created_by']   =  1;
			$params['form']['group_id'] = 0;
			$params['form']['register_date'] = date(DB_DATETIME_FORMAT);
			$params['form']['register_ip'] = $_SERVER['REMOTE_ADDR'];
			$params['form']['status'] = 0;
			$data   = array_intersect_key($params['form'], array_flip($this->_columns));


			$result = $this->insert($data);

			return $result;
		}

		if ($options['task'] == 'edit') {
			$params['form']['modified'] = date(DB_DATETIME_FORMAT);
			$params['form']['modified_by'] = 1;

			$data   = array_intersect_key($params['form'], array_flip($this->_columns));
			$result = $this->update($data, [['id', $params['form']['id']]]);
			if ($result) {
				Session::set('notify', Helper::createNotify('success', 'editDataSuccess'));
			} else {
				Session::set('notify', Helper::createNotify('warning', 'addDataError'));
			}
			return $params['form']['id'];
		}
		if ($options['task'] == 'book-in-cart') {
			$id = $this->randomString(7);
			$username = $_SESSION['user']['info']['username'];
			$books = json_encode($params['form']['book_id']);
			$prices = json_encode($params['form']['price']);
			$quantities = json_encode($params['form']['quantity']);
			$names = json_encode($params['form']['name']);
			$pictures = json_encode($params['form']['picture']);
			$date = date('Y-m-d', time());

			$query = "INSERT INTO `" . TBL_CART . "`(`id`, `username`, `books`, `prices`, `quantities`, `names`, `pictures`, `status`, `date`) VALUE ('$id','$username','$books','$prices','$quantities','$names','$pictures','0','$date')";
			$result = $this->query($query);
			Session::delete('cart');
			return $result;
		}
	}

	private function randomString($length = 5)
	{

		$arrCharacter = array_merge(range('a', 'z'), range(0, 9));
		$arrCharacter = implode('', $arrCharacter);
		$arrCharacter = str_shuffle($arrCharacter);
		$result		= substr($arrCharacter, 0, $length);
		return $result;
	}
}
