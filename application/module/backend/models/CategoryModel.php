<?php
class CategoryModel extends Model
{
    private $_columns = ['id', 'name', 'picture', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering'];
    private $fieldSearchAccepted = ['id', 'name'];
    private $_userInfo;

    //===== __CONSTRUCT ======
    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_CATEGORY);
        $userObj             = Session::get('user');
        $this->_userInfo     = $userObj['info'];
    }

    //===== COUNT ITEMS ======
    public function countItems($arrParam, $options = null)
    {

        //===== VARIABLE ======	
        $filter_search = $arrParam['filter_search'];

        $query[] = "SELECT COUNT(`id`) AS `total`";
        $query[] = "FROM `$this->table`";
        $query[] = "WHERE `id` > 0";

        if ($options == null) {
            //===== FILTER_SEARCH ======
            if (isset($arrParam['filter_search'])) {

                if ($arrParam['key'] == 'all') {
                    $query[]    = "AND (`name` LIKE \"%$filter_search%\" OR `id` LIKE \"%$filter_search%\") ";
                } else if ($arrParam['key'] == 'id') {
                    $query[]    = "AND `id` LIKE \"%$filter_search%\"  ";
                } else if ($arrParam['key'] == 'name') {
                    $query[]    = "AND  `name` LIKE \"%$filter_search%\"  ";
                }
            }
            // FILTER : STATUS
            if (isset($arrParam['status']) && $arrParam['status'] != 'all') {
                $query[] = "AND `status` = '{$arrParam['status']}'";
            }
        } else {
            if ($options['task'] == 'count-active') {
                $query[] = "AND `status` = 1";
            }
            if ($options['task'] == 'count-inactive') {
                $query[] = "AND `status` = 0";
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
        }

        $query = implode(' ', $query);
        $result = $this->fetchRow($query)['total'];
        return $result;
    }

    //===== LIST ITEMS ======
    public function listItems($arrParam, $options = null)
    {
        //===== VARIABLE ======	
        $filter_search = $arrParam['filter_search'];


        $query[] = "SELECT * ";
        $query[] = "FROM `$this->table`";
        $query[] = "WHERE `id` > 0";

        //===== FILTER_SEARCH ======
        if (isset($arrParam['filter_search'])) {

            if ($arrParam['key'] == 'all') {
                $query[]    = "AND (`name` LIKE \"%$filter_search%\" OR `id` LIKE \"%$filter_search%\") ";
            } else if ($arrParam['key'] == 'id') {
                $query[]    = "AND `id` LIKE \"%$filter_search%\"  ";
            } else if ($arrParam['key'] == 'name') {
                $query[]    = "AND  `name` LIKE \"%$filter_search%\"  ";
            }
        }

        //===== ID-PIC ======
        if (!empty($arrParam['id-pic'])) {
            $query[]    = "AND `id`= " . $arrParam['id-pic'];
        }

        //===== STATUS ======
        if ($arrParam['status'] == 'active') {
            $query[]    = "AND `status`= 1";
        }
        if ($arrParam['status'] == 'inactive') {
            $query[]    = "AND `status`= 0";
        }
        //===== FILTER_COLUMN ======
        if (!empty($arrParam['filter_column']) && !empty($arrParam['filter_column_dir'])) {
            $col        = $arrParam['filter_column'];
            $colDir     = $arrParam['filter_column_dir'];
            $query[]     = "ORDER BY `$col` $colDir";
        } else {
            $query[] = "ORDER BY `id` DESC";
        }

        $pagination         = $arrParam['pagination'];
        $totalItemsPerPage  = $pagination['totalItemsPerPage'];
        if ($totalItemsPerPage > 0) {
            $position   = ($pagination['currentPage'] - 1) * $totalItemsPerPage;
            $query[]    = "LIMIT $position, $totalItemsPerPage";
        }

        $query  = implode(" ", $query);
        $result = $this->fetchAll($query);
        return $result;
    }

    //===== INFO ITEM ======
    public function infoItem($arrParam, $option = null)
    {
        if ($option == null) {
            $query[]    = "SELECT `id`, `name`,`picture`, `status`, `ordering`";
            $query[]    = "FROM `$this->table`";
            $query[]    = "WHERE `id` = '" . $arrParam['id'] . "'";
            $query      = implode(" ", $query);
            $result     = $this->fetchRow($query);
            return $result;
        }
    }

    //===== CHANGE STATUS ======
    public function changeStatus($arrParam, $options = null)
    {
        if ($options == null) {
            $modifiedBy = $this->_userInfo['username'];
            $modified   = date(DB_DATETIME_FORMAT, time());
            $status = $arrParam['status'] == 'inactive' ? 'active' : 'inactive';
            $query  = "UPDATE `$this->table` SET `status` = '$status', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = {$arrParam['id']}";
            $this->query($query);
        }

        if ($options['task'] == 'active') {

            $ids = $arrParam['checkbox'];
            $ids = implode(',', $ids);
            $ids = "($ids)";
            $modifiedBy = 1;
            $modified   = date(DB_DATETIME_FORMAT, time());
            $query = "UPDATE `$this->table` SET `status` = 'active', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` IN $ids";
            $this->query($query);
        }

        if ($options['task'] == 'inactive') {
            $ids = $arrParam['checkbox'];
            $ids = implode(',', $ids);
            $ids = "($ids)";
            $modifiedBy = 1;
            $modified   = date(DB_DATETIME_FORMAT, time());
            $query = "UPDATE `$this->table` SET `status` = 'inactive', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` IN $ids";
            $this->query($query);
        }

        if ($options['task'] == 'ajax_status') {

            $id = $arrParam['id'];
            $status = $arrParam['status'] == '1' ? '0' : '1';
            $modifiedBy = $this->_userInfo['username'];
            $modified   = date(DB_DATETIME_FORMAT, time());
            $query = "UPDATE `$this->table` SET `status` = '$status', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = $id";

            $this->query($query);
            return [
                'id'       => $id,
                'status'   => $status,
                'modified' => Helper::showItemHistory($modifiedBy, $modified),
                'link'     => URL::createLink($arrParam['module'], $arrParam['controller'], 'ajaxStatus', ['id' => $id, 'status' => $status]),
            ];
        }
        if ($options['task'] == 'ajax-change-Ordering') {

            $id         = $arrParam['id'];
            $groupACP   = $arrParam['group_acp'] == 0 ? 1 : 0;
            $modifiedBy = $this->_userInfo['username'];
            $modified   = date(DB_DATETIME_FORMAT, time());
            $query = "UPDATE `$this->table` SET `group_acp` = '$groupACP', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = '$id'";
            $this->query($query);

            return [
                'id'        => $id,
                'state'     => $groupACP,
                'modified'  => Helper::showItemHistory($modifiedBy, $modified),
                'link'      => URL::createLink($arrParam['module'], $arrParam['controller'], 'ajaxChangeGroup', ['id' => $id, 'group_acp' => $groupACP])
            ];
        }

        $result = $this->affectedRows();

        if ($result) {
            Session::set('notify', Helper::createNotify('success', 'update'));
        } else {
            Session::set('notify', Helper::createNotify('warning', 'updateError'));
        }
        return $result;
    }

    //===== AJAX ORDERING ======
    public function ajaxOrdering($params, $options = null)
    {
        $id = $params['id'];
        $ordering = $params['ordering'];
        $modifiedBy = $this->_userInfo['username'];
        $modified   = date(DB_DATETIME_FORMAT, time());
        $query = "UPDATE `$this->table` SET `ordering` = $ordering, `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = $id";
        $this->query($query);
        return [
            'id' => $id,
            'modified'  => Helper::showItemHistory($modifiedBy, $modified),
        ];
    }

    //===== DELETE ITEMS ======
    public function deleteItems($arrParam, $options = [])
    {
        $ids = [];
        if (isset($arrParam['id'])) $ids = [$arrParam['id']];
        if (isset($arrParam['checkbox'])) $ids = $arrParam['checkbox'];

        $result = $this->delete($ids);

        if ($result) {
            Session::set('notify', Helper::createNotify('success', 'deleteSuccess'));
        } else {
            Session::set('notify', Helper::createNotify('warning', 'deleteError'));
        }
    }

    //===== SAVEITEM ======
    public function saveItem($params, $options = [])
    {
        if ($options['task'] == 'add') {
            require_once PATH_LIBRARY_EXT . 'Upload.php';
            $uploadObj = new Upload();
            $params['form']['picture']    = $uploadObj->uploadFile($params['form']['picture'], 'category');
            $params['form']['created']    = date(DB_DATETIME_FORMAT);
            $params['form']['created_by'] = $this->_userInfo['username'];
            $params['form']['special'] = 0;

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
            require_once PATH_LIBRARY_EXT . 'Upload.php';
            $uploadObj = new Upload();

            if (!empty($params['form']['picture']['name'])) {
                $uploadObj->removeFile('category', $params['form']['image-hidden']);
                $params['form']['picture']     = $uploadObj->uploadFile($params['form']['picture'], 'category');
            } else {
                $params['form']['picture'] = $params['form']['image-hidden'];
            };
            $params['form']['modified']    = date(DB_DATETIME_FORMAT);
            $params['form']['modified_by'] = $this->_userInfo['username'];
            $params['form']['special']     = 0;

            $data   = array_intersect_key($params['form'], array_flip($this->_columns));
            $result = $this->update($data, [['id', $params['form']['id']]]);
            if ($result) {
                Session::set('notify', Helper::createNotify('success', 'update'));
            } else {
                Session::set('notify', Helper::createNotify('warning', 'updateError'));
            }
            return $params['form']['id'];
        }

        // =================SOLUTION =============***
        if ($options['task'] == 'change-picture') {
            require_once PATH_LIBRARY_EXT . 'Upload.php';
            $uploadObj = new Upload();
            $uploadObj->removeFile('slider', $params['form']['picture-hidden']);
            $params['form']['picture']    = $uploadObj->uploadFile($params['form']['picture'], 'category');
            $params['form']['modified']    = date(DB_DATETIME_FORMAT);
            $params['form']['modified_by'] = Session::get("user")["info"]["username"];
            $data   = array_intersect_key($params['form'], array_flip($this->_columns));
            $result = $this->update($data, [['id', $params['form']['id']]]);
            $dataPicture = $this->listItems($params)[0];
            $arrayGet = array(
                "id" => $params['form']['id'],
                "srcImg" => URL_UPLOAD . "category/" . $dataPicture["picture"]
            );
            return $arrayGet;
        }
        if ($options['task'] == 'missing-picture') {
            $arrayGet = array(
                "id" => $params['form']["id"],
                "srcImg" => "NotFound"
            );
            return $arrayGet;
        }
    }
}
