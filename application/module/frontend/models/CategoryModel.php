<?php
class CategoryModel extends Model{
	
	private $_columns = array('id', 'name', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering');
	private $_userInfo;
	
	//===== CONSTRUCT ======
	public function __construct(){
		parent::__construct();
		$this->setTable(TBL_CATEGORY);
		$userObj 			= Session::get('user');
		$this->_userInfo 	= $userObj['info'];
	}
	
	// ===== LIST ITEM ======
	public function listItem($arrParam, $option = null){
		$query[]	= "SELECT `id`, `name`, `picture`";
		$query[]	= "FROM `$this->table`";
		$query[]	= "WHERE `status`  = '1'";
		$query[]	= "ORDER BY `ordering` ASC";
		$pagination         = $arrParam['pagination'];
		$totalItemsPerPage  = $pagination['totalItemsPerPage'];
		
        if ($totalItemsPerPage > 0) {
            $position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
            $query[]    = "LIMIT $position, $totalItemsPerPage";
        }
		$query		= implode(" ", $query);
		$result		= $this->fetchAll($query);
		return $result;
	}


	//===== COUNT ITEMS ======
	public function countItems($arrParam, $options = null)
    {
        $query[] = "SELECT COUNT(`id`) AS `total`";
        $query[] = "FROM `$this->table`";
        $query[] = "WHERE  `status` ='1'";
        if ($options == null) {
            if (isset($arrParam['status']) && $arrParam['status'] != 'all') $query[] = "AND `status` = '{$arrParam['status']}'";
        }
         $query = implode(' ', $query);
        $result = $this->fetchRow($query)['total'];
        return $result;
    }

	
}