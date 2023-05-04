<?php
class IndexModel extends Model
{
	private $_columns = ['id', 'username', 'email', 'fullname', 'password', 'created', 'created_by', 'modified', 'modified_by', 'register_date', 'register_ip', 'status', 'ordering', 'group_id'];

	//===== __CONSTRUCT ======
	public function __construct()
	{

		parent::__construct();
		$this->setTable(TBL_USER);
	}

	//===== INFO ITEM ======
	public function infoItem($arrParam, $option = null)
	{
		if ($option == null) {
			$username	= $arrParam['form']['username'];
			$password	= md5($arrParam['form']['password']);
			$query[]	= "SELECT `u`.`id`, `u`.`fullname`, `u`.`email`, `u`.`username`, `u`.`group_id`, `g`.`group_acp`, `g`.`privilege_id`";
			$query[]	= "FROM `user` AS `u` LEFT JOIN `group` AS g ON `u`.`group_id` = `g`.`id`";
			$query[]	= "WHERE `username` = '$username' AND `password` = '$password'";

			$query		= implode(" ", $query);
			$result		= $this->fetchRow($query);

			if ($result['group_acp'] == 1) {
				$strPrivilegeID = '';
				$arrPrivilege	= explode(',', $result['privilege_id']);
				foreach ($arrPrivilege as $privilegeID) $strPrivilegeID	.= "'$privilegeID', ";


				$queryP[]	= "SELECT `id`, CONCAT(`module`, '-', `controller`, '-',`action`) AS `name`";
				$queryP[]	= "FROM `" . TBL_PRIVELEGE . "`";
				$queryP[]	= "WHERE `id` IN ($strPrivilegeID'0')";

				$queryP		= implode(" ", $queryP);
				$result['privilege']	= $this->fetchPairs($queryP);
			}

			return $result;
		}
	}

	//===== COUNT ITEMS ======
	public function countItems($params, $options = null)
	{
		$result  = 0;
		$query[] = "SELECT COUNT(`id`) AS `total`";
		if ($options['task'] == 'group') $this->setTable(TBL_GROUP);
		if ($options['task'] == 'user') $this->setTable(TBL_USER);
		if ($options['task'] == 'category') $this->setTable(TBL_CATEGORY);
		if ($options['task'] == 'book') $this->setTable(TBL_BOOK);
		if ($options['task'] == 'slider') $this->setTable(TBL_SLIDER);
		$query[] = "FROM `$this->table`";

		$query  = implode(' ', $query);
		$result = $this->fetchRow($query)['total'];
		return $result;
	}


	//===== SAVE ITEM ======
	public function saveItem($params, $options = [])
	{
		$this->setTable(TBL_USER);
		$params['form']['modified'] = date(DB_DATETIME_FORMAT);
		$params['form']['modified_by'] = 1;
		$data   = array_intersect_key($params['form'], array_flip($this->_columns));


		$result = $this->update($data, [['id', $params['form']['id']]]);

		if ($result) {
			Session::set('notify', Helper::createNotify('success', 'editDataSuccess'));
		} else {
			Session::set('notify', Helper::createNotify('warning', 'addDataError'));
		}
		return $data;
	}
}
