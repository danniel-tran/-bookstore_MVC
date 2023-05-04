<?php
class CategoryModel extends Model{
	
	private $_columns = array('id', 'name', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering');
	private $_userInfo;
	
	//===== __CONSTRUCT ======
	public function __construct(){
		parent::__construct();
			
		$this->setTable(TBL_CATEGORY);
		$userObj 			= Session::get('user');
		$this->_userInfo 	= $userObj['info'];
	}
	
	//===== COUNT ITEM ======
	public function countItem($arrParam, $option = null){
	
		$query[]	= "SELECT COUNT(`id`) AS `total`";
		$query[]	= "FROM `$this->table`";
		$query[]	= "WHERE `id` > 0";
		
		// FILTER : KEYWORD
		if(!empty($arrParam['filter_search'])){
			$keyword	= '"%' . $arrParam['filter_search'] . '%"';
			$query[]	= "AND `name` LIKE $keyword";
		}
	
		// FILTER : STATUS
		if(isset($arrParam['filter_state']) && $arrParam['filter_state'] != 'default'){
			$query[]	= "AND `status` = '" . $arrParam['filter_state']. "'";
		}
	
		$query		= implode(" ", $query);
		$result		= $this->fetchRow($query);
		return $result['total'];
	}
	
	//===== LIST ITEM ======
	public function listItem($arrParam, $option = null){
		if($option == null){
			$query[]	= "SELECT `id`, `name`, `picture`, `status`, `ordering`, `created`, `created_by`, `modified`, `modified_by`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "WHERE `id` > 0";
			
			// FILTER : KEYWORD
			if(!empty($arrParam['filter_search'])){
				$keyword	= '"%' . $arrParam['filter_search'] . '%"';
				$query[]	= "AND `name` LIKE $keyword";
			}
			
			// FILTER : STATUS
			if(isset($arrParam['filter_state']) && $arrParam['filter_state'] != 'default'){
				$query[]	= "AND `status` = '" . $arrParam['filter_state'] . "'";
			}
			
			// SORT
			if(!empty($arrParam['filter_column']) && !empty($arrParam['filter_column_dir'])){
				$column		= $arrParam['filter_column'];
				$columnDir	= $arrParam['filter_column_dir'];
				$query[]	= "ORDER BY `$column` $columnDir";
			}else{
				$query[]	= "ORDER BY `id` DESC";
			}  
			
			// PAGINATION
			$pagination			= $arrParam['pagination'];
			$totalItemsPerPage	= $pagination['totalItemsPerPage'];
			if($totalItemsPerPage > 0){
				$position	= ($pagination['currentPage']-1)*$totalItemsPerPage;
				$query[]	= "LIMIT $position, $totalItemsPerPage";
			}
	
			$query		= implode(" ", $query);
			$result		= $this->fetchAll($query);
			return $result;
		}
		
		if($option['task'] == 'xml-category'){
			$query		= "SELECT `id`, `name`, `picture` FROM `$this->table` WHERE `status` = 1";
			$result		= $this->fetchAll($query);
			return $result;
		}
	}
	
	//===== CHANGE STATUS ======
	public function changeStatus($arrParam, $option = null){
		require_once PATH_LIBRARY_EXT . 'XML.php';
		if($option['task'] == 'change-ajax-status'){
			$status 		= ($arrParam['status'] == 0) ? 1 : 0;
			$modified_by	= $this->_userInfo['username'];
			$modified		= date('Y-m-d', time());
			$id		= $arrParam['id'];
			$query	= "UPDATE `$this->table` SET `status` = $status, `modified` = '$modified', `modified_by` = '$modified_by' WHERE `id` = '" . $id . "'";
	
			$this->query($query);
			
			$result = array(
								'id'		=> $id, 
								'status'	=> $status, 
								'link'		=> URL::createLink('admin', 'category', 'ajaxStatus', array('id' => $id, 'status' => $status))
						); 
			$arrCategories	= $this->listItem($arrParam, array('task' => 'xml-category'));
			XML::createXML($arrCategories, 'categories.xml');
			return $result;
		}
		
		if($option['task'] == 'change-status'){
			$status 		= $arrParam['type'];
			$modified_by	= $this->_userInfo['username'];
			$modified		= date('Y-m-d', time());
			if(!empty($arrParam['cid'])){
				$ids		= $this->createWhereDeleteSQL($arrParam['cid']);
				$query		= "UPDATE `$this->table` SET `status` = $status, `modified` = '$modified', `modified_by` = '$modified_by' WHERE `id` IN ($ids)";
				$this->query($query);
				$arrCategories	= $this->listItem($arrParam, array('task' => 'xml-category'));
				XML::createXML($arrCategories, 'categories.xml');
				Session::set('message', array('class' => 'success', 'content' => 'Có ' . $this->affectedRows(). ' phần tử được thay đổi trạng thái!'));
			}else{
				Session::set('message', array('class' => 'error', 'content' => 'Vui lòng chọn vào phần tử muỗn thay đổi trạng thái!'));
			}
		}
	}
	
	//===== DELETE ITEM ======
	public function deleteItem($arrParam, $option = null){
		if($option == null){
			require_once PATH_LIBRARY_EXT . 'XML.php';
			if(!empty($arrParam['cid'])){
				$ids		= $this->createWhereDeleteSQL($arrParam['cid']);
				
				// Remove image
				$query		= "SELECT `id`, `picture` AS `name` FROM `$this->table` WHERE `id` IN ($ids)";
				$arrImage	= $this->fetchPairs($query);
				require_once PATH_LIBRARY_EXT . 'Upload.php';
				$uploadObj	= new Upload();
				foreach ($arrImage as $value){
					$uploadObj->removeFile('category', $value);
					$uploadObj->removeFile('category', '60x90-' . $value);
				}
				
				// Delte from databae
				$query		= "DELETE FROM `$this->table` WHERE `id` IN ($ids)";
				$this->query($query);
				$arrCategories	= $this->listItem($arrParam, array('task' => 'xml-category'));
				XML::createXML($arrCategories, 'categories.xml');
				Session::set('message', array('class' => 'success', 'content' => 'Có ' . $this->affectedRows(). ' phần tử được xóa!'));
			}else{
				Session::set('message', array('class' => 'error', 'content' => 'Vui lòng chọn vào phần tử muỗn xóa!'));
			}
		}
	}
	
	//===== INFO ITEM ======
	public function infoItem($arrParam, $option = null){
		if($option == null){
			$query[]	= "SELECT `id`, `name`, `picture`, `status`, `ordering`";
			$query[]	= "FROM `$this->table`";
			$query[]	= "WHERE `id` = '" . $arrParam['id'] . "'";
			$query		= implode(" ", $query);
			$result		= $this->fetchRow($query);
			return $result;
		}
	}
	
	//===== SAVE ITEM ======
	public function saveItem($arrParam, $option = null){
		require_once PATH_LIBRARY_EXT . 'Upload.php';
		require_once PATH_LIBRARY_EXT . 'XML.php';
		$uploadObj	= new Upload();
		
		if($option['task'] == 'add'){
			$arrParam['form']['picture']	= $uploadObj->uploadFile($arrParam['form']['picture'], 'category');
			$arrParam['form']['created']	= date('Y-m-d', time());
			$arrParam['form']['created_by']	= $this->_userInfo['username'];
			$data	= array_intersect_key($arrParam['form'], array_flip($this->_columns));
			$this->insert($data);
			Session::set('message', array('class' => 'success', 'content' => 'Dữ liệu được lưu thành công!'));
			
			$arrCategories	= $this->listItem($arrParam, array('task' => 'xml-category'));
			XML::createXML($arrCategories, 'categories.xml');
			
			return $this->lastID();
		}
		if($option['task'] == 'edit'){
			$arrParam['form']['modified']	= date('Y-m-d', time());
			$arrParam['form']['modified_by']= $this->_userInfo['username'];
			
			if($arrParam['form']['picture']['name']==null){
				unset($arrParam['form']['picture']);
			}else{
				$uploadObj->removeFile('category', $arrParam['form']['picture_hidden']);
				$uploadObj->removeFile('category', '60x90-' .  $arrParam['form']['picture_hidden']);
				
				$arrParam['form']['picture']	= $uploadObj->uploadFile($arrParam['form']['picture'], 'category');
			}
			
			$data	= array_intersect_key($arrParam['form'], array_flip($this->_columns));
			$this->update($data, array(array('id', $arrParam['form']['id'])));
			Session::set('message', array('class' => 'success', 'content' => 'Dữ liệu được lưu thành công!'));
			
			$arrCategories	= $this->listItem($arrParam, array('task' => 'xml-category'));
			XML::createXML($arrCategories, 'categories.xml');
			
			return $arrParam['form']['id'];
		}
	}
	
	//===== ORDERING ======
	public function ordering($arrParam, $option = null){
		if($option == null){
			if(!empty($arrParam['order'])){
				$i = 0;
				$modified_by	= $this->_userInfo['username'];
				$modified		= date('Y-m-d', time());
				foreach($arrParam['order'] as $id => $ordering){
					$i++;
					$query	= "UPDATE `$this->table` SET `ordering` = $ordering, `modified` = '$modified', `modified_by` = '$modified_by'  WHERE `id` = '" . $id . "'";
					$this->query($query);
				}
				Session::set('message', array('class' => 'success', 'content' => 'Có ' .$i. ' phần tử được thay đỏi  ordering!'));
			}
		}
	}
}