<?php
class CartModel extends Model
{
    private $_columns = ['id', 'username', 'books', 'prices', 'quantities', 'names', 'pictures', 'status', 'date','create'];
    private $fieldSearchAccepted = ['id', 'name'];
    private $_userInfo;

    //===== __CONSTRUCT ======
    public function __construct()
    {
        parent::__construct();
        $this->setTable(TBL_CART);

        $userObj             = Session::get('user');
        $this->_userInfo     = $userObj['info'];
    }

    //===== LIST ITEMS ======
    public function listItems($arrParam, $options = null)
    {
        //===== VARIABLE ======	

        $query[] = "SELECT `id`, `username`, `books`, `prices`, `quantities`, `names`, `pictures`, `status`, `date`";
        $query[] = "FROM `$this->table`";
        $query[] = "WHERE `id` <> '0'";

        //===== STATUS ======
        if ($arrParam['status'] == 'active') {
            $query[]    = "AND `status`= 1";
        }
        if ($arrParam['status'] == 'inactive') {
            $query[]    = "AND `status`= 0";
        }
        // FIlTER ID
        if (isset($arrParam['id'])) {
            $query[]     = "AND `id`= '" . $arrParam['id'] . "'";
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
    // BOOK IN SHOP
    public function bookInShop()
    {
        $query[] = "SELECT `b`.`id`, `b`.`name`,`b`.`picture`,`b`.`status`, `b`.`category_id`";
        $query[] = "FROM `" . TBL_BOOK . "` AS `b` LEFT JOIN `" . TBL_CATEGORY . "`AS `c`";
        $query[] = "ON `b`.`category_id`= `c`.`id`";
        $query[] = "AND `b`.`category_id`= '1' AND `c`.`id` = '1'";
        $query = implode(" ", $query);
        $result = $this->fetchAll($query);
        return $result;
    }
    // BOOK DETAIL
    public function bookDetailCart($arrParam, $idWhere)
    {
        $query[] = "SELECT * ";
        $query[] = "FROM `" . TBL_BOOK . "`";
        $query[] = "WHERE `id` = '" . $arrParam["$idWhere"] . "'";
        $query = implode(" ", $query);
        $result = $this->fetchRow($query);
        $result["src"] = URL_PUBLIC . "files/book/" . $result["picture"];
        return $result;
    }
    // BOOK CHANGE CART
    public function bookChangeCart($arrParam)
    {
        $arrayNewBook = $this->bookDetailCart($arrParam, "id-book");
        $query = "SELECT * FROM `" . TBL_CART . "` WHERE `id` = '" . $arrParam["idCart"] . "'";
        $arrayCart = $this->fetchRow($query);
        $arrayBook = json_decode($arrayCart["books"]);
        $arrayPrices = json_decode($arrayCart["prices"]);
        $arrayQuantity = json_decode($arrayCart["quantities"]);
        $arrayName = json_decode($arrayCart["names"]);
        $arrayPicture = json_decode($arrayCart["pictures"]);
        foreach ($arrayBook as $key => $value) {
            if ($value == $arrParam["idBookDefault"]) {
                $arrayBook[$key] = $arrParam["id-book"];
                $arrayPrices[$key] = $arrayNewBook["price"] * (1 - $arrayNewBook["sale_off"] / 100);
                $arrayQuantity[$key] = $arrParam["quantity"];
                $arrayName[$key] = $arrayNewBook["name"];
                $arrayPicture[$key] = $arrayNewBook["picture"];
            };
        };
        $arrayBook = json_encode($arrayBook);
        $arrayPrices = json_encode($arrayPrices);
        $arrayQuantity = json_encode($arrayQuantity);
        $arrayName = json_encode($arrayName);
        $arrayPicture = json_encode($arrayPicture);

        // UPDATE
        $data = array(
            "books" => $arrayBook,
            "prices" => $arrayPrices,
            "quantities" => $arrayQuantity,
            "names" => $arrayName,
            "pictures" => $arrayPicture
        );
        foreach ($data as $key => $value) {
            $data[$key] =  str_replace("\\", "", $value);
        };
        $where = array(
            array("id", $arrParam["idCart"], null),
        );
        $result = $this->update($data, $where);
        $flag = false;
        if ($result > 0) {
            $flag = true;
        };
        return $flag;
    }


    //===== COUNT ITEMS ======
    public function countItems($arrParam, $options = null)
    {
        $query[] = "SELECT COUNT(`id`) AS `total`";
        $query[] = "FROM `$this->table`";
        $query[] = "WHERE `id` <> '0'";

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

    // SAVE ITEM 
    public function saveItem($arrParam, $options = null)
    {
        $arrPrice = [];
        $arrNameBook  = [];
        $arrPicture = [];

        foreach ($arrParam["form"]["book-id"] ?? [] as $key => $value) {
            $arrayFlag = [];
            $arrayFlag = array_merge($arrParam, array("id" => $value));
            $arrayBooksInCart = $this->infoBook($arrayFlag, "book");
            $arrPrice[] = $arrayBooksInCart["price"] * (1 - $arrayBooksInCart["sale_off"] / 100);
            $arrNameBook[] = $arrayBooksInCart['name'];
            $arrPicture[] = $arrayBooksInCart['picture'];
        }

        if ($options["task"] == 'add') {
            $arrParam['data']['books'] = json_encode($arrParam["form"]["book-id"]);
            $arrParam['data']['quantities'] = json_encode($arrParam["form"]["book-quantity"]);
            $arrParam['data']['prices'] = json_encode(!empty($arrPrice) ? $arrPrice : []);
            $arrParam['data']['names'] = json_encode(!empty($arrNameBook) ? $arrNameBook : []);
            $arrParam['data']['pictures'] = json_encode(!empty($arrPicture) ? $arrPicture : []);
            $arrParam['data']['status'] = $arrParam['form']['status'];
            $arrParam['data']['date'] = date(DB_DATETIME_FORMAT, strtotime($arrParam["dateOrder"]));
            $arrParam['data']['username'] = Session::get("user")["info"]['username'];
            $arrParam['data']['create'] = date(DB_DATETIME_FORMAT, time());

            // id Cart
            $id  = $this->setIdDatabase();
            $arrParam['data']['id'] =  $id;
            $dataInsert   = array_intersect_key($arrParam['data'], array_flip($this->_columns));
            $result = $this->insertCart($dataInsert, "single");
            if ($result !== 0) {
                Session::set('notify', Helper::createNotify('success', 'addDataSuccess'));
            } else {
                Session::set('notify', Helper::createNotify('warning', 'addDataError'));
            }
            return $id;
        }

        if ($options["task"] == 'edit') {

            $arrParam['data']['books'] = json_encode($arrParam["form"]["book-id"]);
            $arrParam['data']['quantities'] = json_encode($arrParam["form"]["book-quantity"]);
            $arrParam['data']['prices'] = json_encode(!empty($arrPrice) ? $arrPrice : []);
            $arrParam['data']['names'] = json_encode(!empty($arrNameBook) ? $arrNameBook : []);
            $arrParam['data']['pictures'] = json_encode(!empty($arrPicture) ? $arrPicture : []);
            $arrParam['data']['status'] = $arrParam['form']['status'];
            $arrParam['data']['date'] = date(DB_DATETIME_FORMAT, strtotime($arrParam["dateOrder"]));
            $arrParam['data']['username'] = Session::get("user")["info"]['username'];
            $arrParam['data']['create'] = date(DB_DATETIME_FORMAT, time());


            $dataUpdate = array_intersect_key($arrParam['data'], array_flip($this->_columns));;
            $where = array(["id", $arrParam["id"], null]);
            $result = $this->update($dataUpdate, $where);
            if ($result !== 0) {
                Session::set('notify', Helper::createNotify('success', 'addDataSuccess'));
            } else {
                Session::set('notify', Helper::createNotify('warning', 'addDataError'));
            }
            return $arrParam['id'];
        }
    }

    // INSERT CART
    public function insertCart($data, $type = 'single')
    {
        $result = '';
        if ($type == 'single') {
            $newQuery     = $this->createInsertSQL($data);
            $query         = "INSERT INTO `$this->table`(" . $newQuery['cols'] . ") VALUES (" . $newQuery['vals'] . ")
			";

            $result = $this->query($query);
        } else {
            foreach ($data as $value) {
                $newQuery = $this->createInsertSQL($value);
                $query = "INSERT INTO `$this->table`(" . $newQuery['cols'] . ") VALUES (" . $newQuery['vals'] . ")";
                $result = $this->query($query);
            }
        }
        return mysqli_num_rows($result);
    }

    // SET ID DATABASE
    public function setIdDatabase()
    {
        $id  = $this->randomString(7);
        $query = "SELECT * FROM `cart` WHERE `id` = '$id'";
        if (mysqli_num_rows($this->query($query)) != 0) {
            $this->setIdDatabase();
        }
        return $id;
    }
    //===== CHANGE STATUS ======
    public function changeStatus($arrParam, $options = null)
    {
        if ($options == null) {
            $status = $arrParam['status'] == '1' ? '0' : '1';
            $query  = "UPDATE `$this->table` SET `status` = '$status' WHERE `id` = {$arrParam['id']}";
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
            $query      = "UPDATE `$this->table` SET `status` = 0, `modified` = '$modified', `modified_by` = '$modifiedBy' WHERE `id` IN $ids";

            $this->query($query);
        }
        if ($options['task'] == 'ajax_status') {
            $id         = $arrParam['id'];
            $status     = $arrParam['status'] == 1 ? 0 : 1;
            $query      = "UPDATE `$this->table` SET `status` = $status WHERE `id` = '$id'";
            $this->query($query);
            return [
                'query' => $query,
                'id'       => $id,
                'status'   => $status,
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

    //===== DELETE ITEMS ======
    public function deleteItems($arrParam, $options = [])
    {
        $ids = [];
        if (isset($arrParam['id'])) $ids = [$arrParam['id']];
        if (isset($arrParam['checkbox'])) $ids = $arrParam['checkbox'];

        $result = $this->delete($ids);

        if ($result) {
            Session::set('notify', Helper::createNotify('success', "deleteSuccess"));
        } else {
            Session::set('notify', Helper::createNotify('warning', "changeStatus"));
        }
    }

    //===== DELETE BOOK ORDER ======
    public function deleteOrderedItems($arrParam, $options = [])
    {
        $ids = [];
        if (isset($arrParam['book_id'])) $bookIds = [$arrParam['book_id']];
        if (isset($arrParam['checkbox'])) $bookIds = $arrParam['checkbox'];
        $result = $this->updateDeletedItems($arrParam);
        if ($result) {
            Session::set('notify', Helper::createNotify('success', "deleteSuccess"));
        } else {
            Session::set('notify', Helper::createNotify('warning', "changeStatus"));
        }
    }

    //===== DELETE BOOK ORDER ======
    public function updateDeletedItems($arrParam, $options = [])
    {
        $inFo = $this->infoItem($arrParam);
        $arraySum = [
            'books' => json_decode($inFo['books']),
            'prices' => json_decode($inFo['prices']),
            'quantities' => json_decode($inFo['quantities']),
            'names' => json_decode($inFo['names']),
            'pictures' =>  json_decode($inFo['pictures'])
        ];
        $keySelected = array_search($arrParam['book_id'], $arraySum['books']);
        foreach ($arraySum as $key => $value) {
            array_splice($arraySum[$key], $keySelected, 1);
            json_encode($arraySum[$key]);
        };
        $arrUpdate = [];
        foreach ($arraySum as $key => $value) {
            $arrUpdate[$key] = json_encode($value);
        };
        $result = $this->update($arrUpdate, array(['id', $inFo['id']]));
        return $result;
    }

    //===== INFO ITEM ======
    public function infoItem($arrParam, $option = null)
    {
        if ($option == null) {
            $query[]    = "SELECT `id`, `username`, `books`, `prices`, `quantities`, `names`, `pictures`, `status`, `date`";
            $query[]    = "FROM `$this->table`";
            $query[]    = "WHERE `id` = '" . $arrParam['id'] . "'";
            $query      = implode(" ", $query);
            $result     = $this->fetchRow($query);
            return $result;
        }
    }
    //===== INFO BOOK ======
    public function infoBook($arrParam, $table, $option = null)
    {
        if ($option == null) {
            $query[]    = "SELECT  *";
            $query[]    = "FROM `" . $table . "`";
            $query[]    = "WHERE `id` = '" . $arrParam['id'] . "'";
            $query      = implode(" ", $query);
            $result     = $this->fetchRow($query);
            return $result;
        }
    }
    // RANDOM STRING
    public function randomString($length = 5)
    {

        $arrCharacter = array_merge(range('a', 'z'), range(0, 9));
        $arrCharacter = implode('', $arrCharacter);
        $arrCharacter = str_shuffle($arrCharacter);
        $result        = substr($arrCharacter, 0, $length);
        return $result;
    }
}
