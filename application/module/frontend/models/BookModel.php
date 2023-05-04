<?php
class BookModel extends Model
{

	private $_columns = array('id', 'name', 'description', 'price', 'sale_off', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering', 'category_id');
	private $_userInfo;

	//===== CONSTRUCT ======
	public function __construct()
	{
		parent::__construct();
		$this->setTable(TBL_BOOK);
		$userObj 			= Session::get('user');
		$this->_userInfo 	= $userObj['info'];
	}

	//===== LIST ITEM ======
	public function listItem($params, $option = NULL)
	{
		$result = NULL;
		if ($option['task'] == 'relateBook') {
			$id = $params['book_id'];
			$queryID = "SELECT `category_id` FROM `$this->table` WHERE `id` = '$id'";
			$category_id = $this->fetchRow($queryID);
			$category_id = $category_id['category_id'];
			$query[] = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "WHERE `category_id` = '$category_id' AND `id`!='$id'";
		}
		if ($option['task'] == 'newBook1') {
			$query[] 	=  "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "ORDER BY `created` DESC";
			$query[]    = "LIMIT 0,4";
		}
		if ($option['task'] == 'newBook2') {

			$query[] = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "ORDER BY `created` DESC";
			$query[]    = "LIMIT 4,4";
		}
		if ($option['task'] == 'specialBook1') {
			$query[] = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "WHERE `special` = 1";
		}
		if ($option['task'] == 'specialBook2') {
			$query[] = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "WHERE `special` = 1";
			$query[]    = "LIMIT 4,4";
		}
		if ($option['task'] == 'bookInCategory') {

			$idCat 		= $params['category_id'];

			$query[]    = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`,`category_id`";
			$query[]	= "FROM `$this->table`";
			$query[] = $idCat == null ? "" : "WHERE `category_id` = '$idCat'";
			// $query[]	= "WHERE `category_id` = '$idCat'";
		}
		if ($option['task'] == 'category') {
			$query[] = "SELECT `id` ,`name`";
			$query[] = "FROM `" . TBL_CATEGORY . "`";
			$query[] = "WHERE `status` = '1'";
			$query[] = "ORDER BY `ordering` ASC";
		}
		if ($option['task'] == NULL) {
			$query[]    = "SELECT `b`.`id` ,`b`.`name`,`b`.`picture`,`b`.`price`,`b`.`sale_off`,`b`.`special`,`b`.`category_id`";
			$query[]	= "FROM `$this->table` AS `b`";
			$query[]	= "INNER JOIN `category` AS `c`";
			$query[]	= "ON `c`.`id` = `b`.`category_id`";
			$query[]	= "AND `b`.`status` = 1";
			$query[]	= "AND `c`.`status` = 1";

			$pagination         = $params['pagination'];
			$totalItemsPerPage  = $pagination['totalItemsPerPage'];
			if (isset($params['bookCategory'])) {
				$query[] = "AND `b`.`category_id` = " . $params['bookCategory'] . "";
			};
			if ($totalItemsPerPage > 0) {
				$position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
				$query[]    = "LIMIT $position, $totalItemsPerPage";
			}
			if (isset($params['sort-price'])) {
				if ($params['sort-price'] == 'price_asc') {
					$query[] = "ORDER BY `b`.`price` ASC";
				}
				if ($params['sort-price'] == 'price_desc') {
					$query[] = "ORDER BY `b`.`price` DESC";
				}
				if ($params['sort-price'] == 'latest') {
					$query[] = "ORDER BY `b`.`created` DESC";
				}
			}
		}
		// if ($option['task'] == NULL) {
		// 	$query[]    = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`,`category_id`";
		// 	$query[]	= "FROM `$this->table`";
		// 	$pagination         = $params['pagination'];
		// 	$totalItemsPerPage  = $pagination['totalItemsPerPage'];
		// 	if (isset($params['bookCategory'])) {
		// 		$query[] = "WHERE`category_id` = " . $params['bookCategory'] . "";
		// 	};
		// 	if ($totalItemsPerPage > 0) {
		// 		$position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
		// 		$query[]    = "LIMIT $position, $totalItemsPerPage";
		// 	}
		// 	if (isset($params['sort-price'])) {
		// 		if ($params['sort-price'] == 'price_asc') {
		// 			$query[] = "ORDER BY `price` ASC";
		// 		}
		// 		if ($params['sort-price'] == 'price_desc') {
		// 			$query[] = "ORDER BY `price` DESC";
		// 		}
		// 		if ($params['sort-price'] == 'latest') {
		// 			$query[] = "ORDER BY `created` DESC";
		// 		}
		// 	}
		// }
		if ($option['task'] == 'infoBook') {
			$idBook = $params['book_id'];
			$query[]    = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`,`category_id`,`sort_description`,`description`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "WHERE `id` = '$idBook'";
		}

		$query = implode(" ", $query);
		$result = $this->fetchAll($query);
		return $result;
	}

	//===== COUNT ITEMS ======
	public function countItems($arrParam, $options = null)
	{
		$query[] = "SELECT COUNT(`id`) AS `total`";
		$query[] = "FROM `$this->table`";
		$query[] = "WHERE `id` > 0";
		if ($options == null) {
			if (isset($arrParam['status']) && $arrParam['status'] != 'all') $query[] = "AND `status` = '{$arrParam['status']}'";
		}
		if ($options['task'] == 'count-active') {
			$query[] = "AND `status` = 'active'";
		}
		if ($options['task'] == 'count-inactive') {
			$query[] = "AND `status` = 'inactive'";
		}
		// FILTER : KEYWORD
		if (!empty($arrParam['search'])) {
			$query[] = "AND (";
			$keyword    = "'%{$arrParam['search']}%'";
			foreach ($this->fieldSearchAccepted as $field) {
				$query[] = "`$field` LIKE $keyword";
				$query[] = "OR";
			}
			array_pop($query);
			$query[] = ")";
		}
		$query = implode(' ', $query);
		$result = $this->fetchRow($query)['total'];
		return $result;
	}

	//===== INFO ITEM ======
	public function infoItem($arrParam, $option = null)
	{
		if ($option == null) {
			$idBook = $arrParam['book_id'];
			$query[]    = "SELECT `id` ,`name`,`picture`,`price`,`sale_off`,`special`,`category_id`,`description`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "WHERE `id` = '$idBook'";
			$query		= implode(" ", $query);

			$result		= $this->fetchRow($query);
			return $result;
		}
	}

	//===== GET ID BOOK ======
	public function getIDBook($arrParam)
	{
		$flag  		   = false;
		$idBook 	   = $arrParam['book_id'];
		$query[]       = "SELECT `id`";
		$query[]       = "FROM `$this->table`";
		$query[]       = "WHERE `id` >0";
		$query  	   = implode(" ", $query);
		$result		   = $this->fetchAll($query);
		foreach ($result as $key => $value) {
			if ($value['id'] == $idBook) {
				$flag = true;
				break;
			} else {
				$flag = false;
			}
		}
		return $flag;
	}
}
