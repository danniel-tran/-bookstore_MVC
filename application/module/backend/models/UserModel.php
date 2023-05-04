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
			$result = ['default' => '- Select Group ID -'] + $result;
		}
		return $result;
	}



	//===== AJAX STATUS ======
	public function ajaxStatus($arrParam, $option = null)
	{
		$id         = $arrParam['id'];
		$modified   = date(DB_DATETIME_FORMAT);
		$modifiedBy = 'admin';
		$status		= ($arrParam['status'] == 1) ? 0 : 1;
		$query 		= "UPDATE `$this->table` SET `status`= $status, `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id`= $id";
		$this->query($query);
		return [
			'id' => $id,
			'status' => $status,
			'link' => URL::createLink($arrParam['module'], $arrParam['controller'], 'ajaxStatus', ['id' => $id, 'status' => $status]),
			'modified'  => Helper::showItemHistory($modifiedBy, $modified),
		];
	}

	//===== AJAX CHANGE GROUP  ======
	public function ajaxChangeGroup($arrParam, $option = null)
	{
		$id         = $arrParam['id'];
		$modified   = date(DB_DATETIME_FORMAT);
		$modifiedBy = 'admin';
		$group_id   = $arrParam['group_id'];
		$query      = "UPDATE `$this->table` SET `group_id`= '$group_id' , `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id`= $id";
		$this->query($query);
		return [
			'id' => $id,
			'group_id' => $group_id,
			'link' => URL::createLink($arrParam['module'], $arrParam['controller'], 'ajaxChangeGroup', ['id' => $id, 'group_id' => $group_id]),
			'modified'  => Helper::showItemHistory($modifiedBy, $modified),

		];
	}

	//===== CHANGE STATUS ======
	public function changeStatus($arrParam, $option = null)
	{
		if ($option['task'] = 'active') {
			$status = ($arrParam['action'] == 'active') ? 1 : 0;
			if (!empty($arrParam['checkbox'])) {
				$ids = $this->createWhereDeleteSQL($arrParam['checkbox']);
			}
		}
		if ($option['task'] = 'inactive') {
			$status = ($arrParam['action'] == 'inactive') ? 0 : 1;
			if (!empty($arrParam['checkbox'])) {
				$ids = $this->createWhereDeleteSQL($arrParam['checkbox']);
			}
		}
		$modifiedBy = 1;
		$modified   = date(DB_DATETIME_FORMAT, time());
		$query = "UPDATE `$this->table` SET `status`= $status ,`modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` IN ($ids)";

		$this->query($query);

		$result = $this->affectedRows();

		if ($result) {
			Session::set('notify', Helper::createNotify('success', 'update'));
		} else {
			Session::set('notify', Helper::createNotify('warning', 'updateError'));
		}
		return $result;
	}


	//===== DELETE ITEMS ======
	public function deleteItems($arrParam, $options = [])
	{
		$ids = [];
		if (isset($arrParam['id'])) $ids       = [$arrParam['id']];
		if (isset($arrParam['checkbox'])) $ids = $arrParam['checkbox'];
		$this->delete($ids);

		$result = $this->affectedRows();
		if ($result) {
			Session::set('notify', Helper::createNotify('success', 'deleteSuccess'));
		} else {
			Session::set('notify', Helper::createNotify('warning', 'deleteError'));
		}
		return $result;
	}

	//===== INFO ITEM ======
	public function infoItem($arrParam, $option = null)
	{
		if ($option == null) {
			$query[]    = "SELECT `id`, `username`, `email`, `fullname`, `password`, `created`, `created_by`, `modified`, `modified_by`, `register_date`, `register_ip`, `status`, `ordering`, `group_id`";
			$query[]    = "FROM `$this->table`";
			$query[]    = "WHERE `id` = '" . $arrParam['id'] . "'";
			$query      = implode(" ", $query);
			$result     = $this->fetchRow($query);
			return $result;
		}
	}


	//===== SAVE ITEM ======
	public function saveItem($params, $options = [])
	{
		if ($options['task'] == 'add') {
			$params['form']['created']      = date(DB_DATETIME_FORMAT);
			$params['form']['created_by']   =  1;
			$data   = array_intersect_key($params['form'], array_flip($this->_columns));

			$result = $this->insert($data);
			if ($result) {
				Session::set('notify', Helper::createNotify('success', 'addDataSuccess'));
			} else {
				Session::set('notify', Helper::createNotify('warning', 'addDataError'));
			}
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
	}

	//===== AJAX ORDERING ======
	public function ajaxOrdering($params, $options = [])
	{
		$id         = $params['id'];
		$ordering   = $params['ordering'];
		$modified   = date(DB_DATETIME_FORMAT);
		$modifiedBy = 'admin';
		$query      = "UPDATE `$this->table` SET `ordering` = $ordering, `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = $id";
		$this->query($query);
		return [
			'id'       => $id,
			'modified' => Helper::showItemHistory($modifiedBy, $modified),
		];
	}

	//===== RESET PASSWORD ======
	public function resetPassword($params, $options = [])
	{
		$id = $params['id'];
		$newPassword = md5($params['new-password']);

		$query = "UPDATE `$this->table` SET `password` = '$newPassword' WHERE `id` = '$id'";
		$this->query($query);
		$result = $this->affectedRows();
		if ($result) {
			if ($result) {
				Session::set('notify', Helper::createNotify('success', 'reset_pas_success'));
			} else {
				Session::set('notify', Helper::createNotify('warning', 'reset_pas_error'));
			}
		}
	}
}
