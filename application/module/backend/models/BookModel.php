<?php
class BookModel extends Model
{
    private $_columns = ['id', 'name', 'description', 'price', 'picture', 'sale_off', 'created', 'created_by', 'modified', 'modified_by', 'status', 'ordering', 'category_id', 'special', 'detail_picture', 'order-thumb'];
    private $fieldSearchAccepted = ['id', 'name'];
    private $_userInfo;

    //===== __CONSTRUCT ======
    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_BOOK);

        $userObj             = Session::get('user');
        $this->_userInfo     = $userObj['info'];
    }

    //===== ITEM INSELECT BOX ======
    public function itemInSelectBox($params, $options = null)
    {
        $query  = "SELECT `id`, `name` FROM `" . TBL_CATEGORY . "`";
        $result = $this->fetchPairs($query);
        if ($options == 'add-default') {
            $result = ['default' => '- Select Category -'] + $result;
        }
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

        if (isset($arrParam['filter_category_id']) && $arrParam['filter_category_id'] != 'default') {
            $query[] = "AND `category_id` = {$arrParam['filter_category_id']}";
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

        $query[] = "SELECT `b`.`id`, `b`.`name`,`b`.`picture`,`b`.`special`,`b`.`price`,`b`.`sale_off`,`b`.`ordering`, `b`.`created`, `b`.`created_by`, `b`.`status`, `b`.`category_id`";
        $query[] = "FROM `$this->table` AS `b` LEFT JOIN `" . TBL_CATEGORY . "`AS `c` ON `b`.`category_id`= `c`.`id`";
        $query[] = "WHERE `b`.`id` > 0";

        //===== FILTER_SEARCH ======
        if (isset($arrParam['filter_search'])) {
            if ($arrParam['key'] == 'all') {
                $query[]    = "AND (`b`.`name` LIKE \"%$filter_search%\" OR `b`.`id` LIKE \"%$filter_search%\") ";
            } else if ($arrParam['key'] == 'id') {
                $query[]    = "AND `b`.`id` LIKE \"%$filter_search%\"  ";
            } else if ($arrParam['key'] == 'name') {
                $query[]    = "AND `b`. `name` LIKE \"%$filter_search%\"  ";
            }
        }

        // FILTER : CATEGORY
        if (isset($arrParam['filter_category_id']) && $arrParam['filter_category_id'] != 'default') {
            $query[] = "AND `b`.`category_id` = {$arrParam['filter_category_id']}";
        }

        // FILTER : STATUS
        if (isset($arrParam['status']) && $arrParam['status'] != 'all') {
            $query[] = "AND `b`.`status` = '{$arrParam['status']}'";
        }

        $query[]    = "ORDER BY `id` ASC";

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
            $query[]    = "SELECT `id`, `name`, `description`, `price`, `special`, `sale_off`, `picture`, `created`, `created_by`, `modified`, `modified_by`, `status`, `ordering`, `category_id`, `detail_picture`, `order-thumb` ";
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
            $modifiedBy = $this->_userInfo;
            $modified   = date(DB_DATETIME_FORMAT, time());
            $status = $arrParam['status'] == '1' ? '1' : '0';
            $query  = "UPDATE `$this->table` SET `status` = '$status', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = {$arrParam['id']}";
            $this->query($query);
        }

        if ($options['task'] == 'active') {

            $ids        = $arrParam['checkbox'];
            $ids        = implode(',', $ids);
            $ids        = "($ids)";
            $modifiedBy = $this->_userInfo['username'];
            $modified   = date(DB_DATETIME_FORMAT, time());
            $query      = "UPDATE `$this->table` SET `status` = 'active', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` IN $ids";
            $this->query($query);
        }

        if ($options['task'] == 'inactive') {

            $ids        = $arrParam['checkbox'];
            $ids        = implode(',', $ids);
            $ids        = "($ids)";
            $modifiedBy = $this->_userInfo['username'];
            $modified   = date(DB_DATETIME_FORMAT, time());
            echo  $query      = "UPDATE `$this->table` SET `status` = 0, `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` IN $ids";

            $this->query($query);
        }
        if ($options['task'] == 'ajax_status') {


            $id         = $arrParam['id'];
            $status     = $arrParam['status'] == 1 ? 0 : 1;
            $modifiedBy = $this->_userInfo['username'];
            $modified   = date(DB_DATETIME_FORMAT, time());
            $query      = "UPDATE `$this->table` SET `status` = '$status', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = $id";
            $this->query($query);
            return [
                'id'       => $id,
                'status'   => $status,
                'modified' => Helper::showItemHistory($modifiedBy, $modified),
                'link'     => URL::createLink($arrParam['module'], $arrParam['controller'], 'ajaxStatus', ['id' => $id, 'status' => $status]),
            ];
        }
        if ($options['task'] == 'ajax_special') {

            $id         = $arrParam['id'];
            $special    = $arrParam['special'] == 1 ? 0 : 1;
            $modifiedBy = $this->_userInfo['username'];
            $modified   = date(DB_DATETIME_FORMAT, time());
            $query      = "UPDATE `$this->table` SET `special` = '$special', `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = $id";
            $this->query($query);
            return [
                'id'       => $id,
                'special'   => $special,
                'modified' => Helper::showItemHistory($modifiedBy, $modified),
                'link'     => URL::createLink($arrParam['module'], $arrParam['controller'], 'ajaxSpecial', ['id' => $id, 'special' => $special]),
            ];
        }

        if ($options['task'] == 'change-group-acp') {
            $id         = $arrParam['id'];
            $groupACP   = $arrParam['group_acp'] == 0 ? 1 : 0;
            $modifiedBy = $this->_userInfo['username'];
            $modified   = date(DB_DATETIME_FORMAT, time());
            $query = "UPDATE `$this->table` SET `group_acp` = $groupACP, `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = $id";
            $this->query($query);

            return [
                'id'        => $id,
                'state'     => $groupACP,
                'modified'  => Helper::showItemHistory($modifiedBy, $modified),
                'link'      => URL::createLink($arrParam['module'], $arrParam['controller'], 'changeGroupACP', ['id' => $id, 'group_acp' => $groupACP])
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

    //===== CHANGE CATEGORY ======
    public function changeCategory($params, $options = null)
    {
        $id         = $params['id'];
        $categoryId = $params['category_id'];
        $modifiedBy = $this->_userInfo['username'];
        $modified   = date(DB_DATETIME_FORMAT, time());
        $query      = "UPDATE `$this->table` SET `category_id` = $categoryId, `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` = $id";
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
        $this->deleteImageFile($ids);
        $result = $this->delete($ids);
        if ($result) {
            Session::set('notify', Helper::createNotify('success', SUCCESS_DELETE));
        } else {
            Session::set('notify', Helper::createNotify('warning', FAIL_ACTION));
        }
    }

    public function deleteImageFile($ids)
    {
        $newWhere     = $this->createWhereDeleteSQL($ids);
        $query = "SELECT `picture`, `detail_picture` FROM `$this->table` WHERE `id` IN ($newWhere)";
        require_once PATH_LIBRARY_EXT . 'Upload.php';
        $uploadImg = new Upload();
        if (!empty($query)) {
            $resultQuery = $this->query($query);
            if (mysqli_num_rows($resultQuery) > 0) {
                while ($row = mysqli_fetch_assoc($resultQuery)) {
                    $uploadImg->removeFile("book", $row["picture"]);
                    $thumb = json_decode($row["detail_picture"]);
                    foreach ($thumb as $key => $value) {
                        $uploadImg->removeFile("book/picture-detail", $value);
                    }
                }
                mysqli_free_result($resultQuery);
            }
        }
        $result = $this->fetchAll($query);
        $return = false;
        foreach ($result as $key => $value) {
            if (empty($value)) {
                $return = true;
            } else {
                $return = false;
            }
        }
        return $return;
    }


    //=====**************** SAVE COPY ITEM **************======
    public function saveItem($params, $options = [])
    {

        $upThumbFile = [];
        $thumbOrdering = [];
        $tmpFile = [];
        require_once PATH_LIBRARY_EXT . 'Upload.php';
        $uploadObj = new Upload();
        if ($options['task'] == 'add') {

            $params['form']['name']              = $params['form']['name'];
            $params['form']['short_description'] = $params['form']['short_description'];
            $params['form']['description']       = $params['form']['description'];
            $params['form']['price']             = $params['form']['price'];
            $params['form']['sale_off']          = $params['form']['sale_off'];
            $params['form']['status']            = $params['form']['status'];
            $params['form']['special']           = $params['form']['special'];
            $params['form']['created']           = date(DB_DATETIME_FORMAT);
            $params['form']['created_by']        =  $this->_userInfo['username'];
            $params['form']['picture']           = $uploadObj->uploadFile($params['form']['picture'], 'book');
            // thumb
            $arrayOrder = $params["form"]["thumbOrdering"] ?? [];
            foreach ($arrayOrder as $key => $value) {
                $thumbOrdering[$key] = $value;
                $upThumbFile[$key] = $uploadObj->uploadFile($params['form']['upThumbFile'], 'book');
                $tmpFile[$key] = $params["form"]["upThumbFile"]["tmp_name"][$key];
            };
            $arrange = [];
            foreach ($thumbOrdering as $key => $value) {
                $arrange[$value] = $upThumbFile[$key];
            };
            ksort($arrange);
            $arrayArrange = [];
            foreach ($arrange as $key => $value) {
                $arrayArrange[] = $value;
            };
            $arrange = $arrayArrange;
            sort($thumbOrdering);



            // =====
            $params['form']["detail_picture"]   = json_encode($arrange);
            $params['form']["order-thumb"]    = json_encode($thumbOrdering);

            $data                                = array_intersect_key($params['form'], array_flip($this->_columns));
            $result                              = $this->insert($data);
            if ($result) {
                Session::set('notify', Helper::createNotify('success', 'addDataSuccess'));
            } else {
                Session::set('notify', Helper::createNotify('warning', 'addDataError'));
            }
            return $result;
        }

        if ($options['task'] == 'edit') {
            $params['form']['name']              = $params['form']['name'];
            $params['form']['short_description'] = $params['form']['short_description'];
            $params['form']['description']       = $params['form']['description'];
            $params['form']['price']             = $params['form']['price'];
            $params['form']['sale_off']          = $params['form']['sale_off'];
            $params['form']['special']           = $params['form']['special'];
            $params['form']['created']           = date(DB_DATETIME_FORMAT);
            $params['form']['created_by']         =  $this->_userInfo['username'];
            $params['form']['status']            = $params['form']['status'];
            if (!empty($params['form']['picture']['tmp_name'])) {
                $uploadObj->removeFile('book', $params['form']['image-hidden']);
                $params['form']['picture']           = $uploadObj->uploadFile($params['form']['picture'], 'book');
            } else {
                $params['form']['picture'] = $params['form']['image-hidden'];
            };

            // thumb
            $arrayOrder = $params["form"]["thumbOrdering"] ?? [];
            foreach ($arrayOrder as $key => $value) {
                $thumbOrdering[$key] = $value;
                $arrayUpload = [
                    "name" => $params["form"]["upThumbFile"]["name"][$key],
                    "tmp_name" => $params["form"]["upThumbFile"]["tmp_name"][$key]
                ];
                if (isset($params['form']['thumb-name'][$key])) {
                    if (!empty($params["form"]["upThumbFile"]["tmp_name"][$key])) {
                        $uploadObj->removeFile('book/picture-detail', $params['form']['thumb-name'][$key]);
                        $upThumbFile[$key] = $uploadObj->uploadFile($arrayUpload, 'book/picture-detail');
                        $tmpFile[$key] = $params["form"]["upThumbFile"]["tmp_name"][$key];
                    } else {
                        $upThumbFile[$key] = $params['form']['thumb-name'][$key];
                        $tmpFile[$key] = "";
                    };
                } else {
                    $upThumbFile[$key] = $uploadObj->uploadFile($params['form']['upThumbFile'], 'book');
                    $tmpFile[$key] = $params["form"]["upThumbFile"]["tmp_name"][$key];
                    $upThumbFile[$key] = $uploadObj->uploadFile($arrayUpload, 'book/picture-detail');
                };
            };
            $arrange = [];
            foreach ($thumbOrdering as $key => $value) {
                $arrange[$value] = $upThumbFile[$key];
            };
            ksort($arrange);
            $arrayArrange = [];
            foreach ($arrange as $key => $value) {
                $arrayArrange[] = $value;
            };
            $arrange = $arrayArrange;
            sort($thumbOrdering);

            $params['form']["detail_picture"] = json_encode($arrange);
            $params['form']["order-thumb"]    = json_encode($thumbOrdering);
            // =====
            $data                                = array_intersect_key($params['form'], array_flip($this->_columns));
            $result                              = $this->update($data, [['id', $params['id']]]);
            if ($result) {
                Session::set('notify', Helper::createNotify('success', "editDataSuccess"));
            } else {
                Session::set('notify', Helper::createNotify('warning', 'addDataError'));
            }
            return $params['form']['id'];
        }
    }
    //===== AJAX ORDERING ======
    public function ajaxOrdering($params, $options = [])
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
}
