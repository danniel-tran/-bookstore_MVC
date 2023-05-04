<?php
class IndexModel extends Model
{

	private $_columns = array(
		'id',
		'username',
		'email',
		'fullname',
		'password',
		'created',
		'created_by',
		'modified',
		'modified_by',
		'register_date',
		'register_ip',
		'status',
		'ordering',
		'group_id'
	);

	//===== CONSTRUCT ======
	public function __construct()
	{
		parent::__construct();
		$this->setTable(TBL_BOOK);
	}


	//===== INFO ITEM ======
	public function infoItem($arrParam, $options = null)
	{
		if ($options == null) {
			$username = $arrParam['form']['username'];
			$password = md5($arrParam['form']['password']);
			$query[] = "SELECT `u`.`id`, `u`.`fullname`, `u`.`email`,`u`.`username`, `u`.`group_id`, `g`.`group_acp`";
			$query[]    = "FROM `user` AS   `u` LEFT JOIN `group` AS `g` ON `u`.`group_id`= `g`.`id`";
			$query[] = "WHERE `username` = '$username' AND `password` = '$password'";

			$query = implode(" ", $query);
			$result = $this->fetchRow($query);
			return $result;
		}

		if ($options['task'] == 'quick-view') {

			$id 	 = $arrParam['id'];
			$query[] = "SELECT `name`, `short_description`, `price`,`sale_off`, `picture`";
			$query[] = "FROM `$this->table`";
			$query[] = "WHERE `id` = '$id' ";
			$query  = implode(" ", $query);
			$result = $this->fetchRow($query);

			$salePrice = ($result['price']  - ($result['price'] * $result['sale_off'] / 100));
			$salePriceFormat = number_format($salePrice) . ' đ';
			$priceFormat = number_format($result['price']) . ' đ';
			return [
				'id' => $id,
				'picture' => $result['picture'],
				'name' => $result['name'],
				'short_description' => $result['short_description'],
				'price' => $result['price'],
				'salePrice'	=> $salePrice,
				'salePriceFormat' => $salePriceFormat,
				'priceFormat' => $priceFormat,
			];
		}
	}

	//===== LIST ITEMS ======
	public function listItems($arrParam, $options = null)
	{
		$query[] = "SELECT `id`, `name`, `picture`, `price`, `sale_off`, `category_id`";
		$query[] = "FROM `$this->table`";
		$query[] = "WHERE `special` = 1";

		// FILTER : KEYWORD
		if (!empty($arrParam['search'])) {
			$query[] = "AND (";
			$keyword = "'%{$arrParam['search']}%'";
			foreach ($this->fieldSearchAccepted as $field) {
				$query[] = "`$field` LIKE $keyword";
				$query[] = "OR";
			}
			array_pop($query);
			$query[] = ")";
		}
		// FILTER : STATUS
		if (isset($arrParam['status']) && $arrParam['status'] != 'all') {
			$query[] = "AND `status` = '{$arrParam['status']}'";
		}

		$query[]    = "ORDER BY `id` DESC";
		// $pagination         = $arrParam['pagination'];
		// $totalItemsPerPage  = $pagination['totalItemsPerPage'];
		// if ($totalItemsPerPage > 0) {
		//     $position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
		//     $query[]    = "LIMIT $position, $totalItemsPerPage";
		// }

		$query  = implode(" ", $query);
		$result = $this->fetchAll($query);

		return $result;
	}

	//SLIDER
	public function listSlider()
	{
		$query[] = "SELECT `status`, `picture` , `link`";
		$query[] = "FROM `slider`";
		$query  = implode(" ", $query);
		$result = $this->fetchAll($query);

		return $result;
	}



	//===== LIST DANH MUC NOI BAT ======
	public function listDanhMucNoiBat($arrParam, $options = null)
	{
		$query[]       = "SELECT `id`, `name`";
		$query[]       = "FROM `" . TBL_CATEGORY . "`";
		$query[]       = "WHERE `status` = 1";
		$query[]       = "ORDER BY `id` ASC";
		$query  	   = implode(" ", $query);
		$result		   = $this->fetchAll($query);

		return $result;
	}

	//===== LAY ID ======
	public function layid()
	{
		$query[] = "SELECT `id`";
		$query[] = "FROM `" . TBL_CATEGORY . "`";
		$query[] = "WHERE `status` = 1";
		$query  = implode(" ", $query);

		$result = $this->fetchAll($query);


		foreach ($result as $value) {
			$id = $value['id'];
			$arr[] = $value['id'];
		}
		$chuoi = implode(',', $arr);

		$query1[]        = "SELECT `id`, `name`, `picture`, `price`, `sale_off`, `category_id`";
		$query1[]        = "FROM `book`";
		$query1[]        = "WHERE `category_id` IN({$chuoi}) ";
		$query1 		 = implode(" ", $query1);
		$result1 		 = $this->fetchAll($query1);

		return $result1;
	}

	//===== LIST ITEM ======
	public function listItem($params, $option = NULL)
	{

		$result = NULL;
		if ($option['task'] == 'specialBook') {
			$query[] = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "WHERE `special` = 1";
		}

		if ($option['task'] == 'bookInCategory') {
			$ids = "";
			$special = "SELECT `id`,`name` FROM `" . TBL_CATEGORY . "`";
			$special_id = $this->fetchAll($special);
			foreach ($special_id as $key => $value) {
				$ids .= ',' . $value['id'];
				$ids = ltrim($ids, ',');
			}
			$query[] = "SELECT `id`, `name`, `picture`, `created`, `created_by`, `modified`, `modified_by`, `status`, `ordering`,`category_id` FROM `book` WHERE `category_id` IN ($ids) ";
		}
		if ($option['task'] == 'specialCategory') {
			$query[]	= "SELECT `id` ,`name`";
			$query[]	= "FROM `" . TBL_CATEGORY . "`";
			$query[]	= "WHERE `special-category` = 1	";
		}
		$query = implode(" ", $query);
		$result = $this->fetchAll($query);

		return $result;
	}
}
