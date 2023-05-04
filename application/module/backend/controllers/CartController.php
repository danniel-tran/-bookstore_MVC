<?php
class CartController extends Controller
{

    //===== CONSTRUCT ======
    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('admin/theme_admin/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
        $this->_moduleName = $this->_arrParam['module'];
        $this->_controllerName = $this->_arrParam['controller'];
    }

    //===== INDEX ACTION ======
    public function indexAction()
    {
        $totalItems = $this->_model->countItems($this->_arrParam);
        $configPagination = ['totalItemsPerPage'    => 4, 'pageRange' => 3];
        $this->setPagination($configPagination);

        $this->_view->pagination        = new Pagination($totalItems, $this->_pagination);
        $this->_view->countActive = $this->_model->countItems($this->_arrParam, ['task' => 'count-active']);
        $this->_view->countInactive = $this->_model->countItems($this->_arrParam, ['task' => 'count-inactive']);
        $this->_view->Items = $this->_model->listItems($this->_arrParam);
        $this->_view->render("{$this->_controller}/index");
    }
    // =======FORM CART
    public function formAction()
    {
        $this->_view->_title         = ucfirst($this->_controller) . ' Manager :: Add';

        if ($this->_arrParam['form']['token'] > 0) {
            $bookArr = $this->_arrParam['form']["book-id"];
            $bookQuantity = $this->_arrParam['form']["book-quantity"];
            if (!empty($this->_arrParam['form']["book-id"]) || !empty($this->_arrParam['form']["book-quantity"])) {
                foreach ($bookArr as $key => $value) {
                    if ($value == "default" && (empty($bookQuantity[$key]) || $bookQuantity[$key] == 0)) {
                        unset($bookArr[$key]);
                        unset($bookQuantity[$key]);
                    }
                }
            }
            $this->_validate->addSourceElement("book-id", $bookArr);
            $this->_validate->addSourceElement("book-quantity", $bookQuantity);
            $this->_validate->validate($this->_model);
            if ($this->_validate->isValid() == false) {
                $this->_view->errors = $this->_validate->showErrorsAdmin();
            } else {
                $task = (isset($this->_arrParam['form']['id'])) ? 'edit' : 'add';
                $id = $this->_model->saveItem($this->_arrParam, ['task' => $task]);
                if ($this->_arrParam['type'] == 'save-close') URL::redirect($this->_module, $this->_controller, 'index');
                if ($this->_arrParam['type'] == 'save-new') URL::redirect($this->_module, $this->_controller, 'form');
                if ($this->_arrParam['type'] == 'save') URL::redirect($this->_module, $this->_controller, 'form', ['id' => $id]);
            };
        };
        if (isset($this->_arrParam['id'])) {
            $this->_view->_title         = ucfirst($this->_controller) . ' Manager :: Edit';
            $this->_arrParam['form']     = $this->_model->infoItem($this->_arrParam);
            $this->_view->arrBookInCart  = $this->_model->bookInShop();
            if (empty($this->_arrParam['form'])) URL::redirect($this->_module, $this->_controller, 'index');
        }

        $this->_view->arrParam = $this->_arrParam;
        $this->_view->render($this->_controller . '/form');
    }
    //===== AJAX STATUS ACTION ======
    public function ajaxStatusAction()
    {
        $result = $this->_model->changeStatus($this->_arrParam, ['task' => 'ajax_status']);
        echo json_encode($result);
    }

    //===== DELETE ACTION ======
    public function deleteAction()
    {
        $this->_model->deleteItems($this->_arrParam);
        URL::redirect($this->_module, $this->_controller, 'index');
    }

    //===== DELETE BOOK ORDER ACTION ======
    public function deleteBookOrderAction()
    {
        $this->_model->deleteOrderedItems($this->_arrParam);
        URL::redirect($this->_module, $this->_controller, 'detail', ['id' => $this->_arrParam['id']]);
    }

    //===== DETAIL ACTION ======
    public function detailAction()
    {
        $this->_view->booksInShop = $this->_model->bookInShop();
        $this->_view->_title         = ucfirst($this->_controller) . ' :: Edit';
        $configPagination = ['totalItemsPerPage'    => -1, 'pageRange' => 3];
        $this->setPagination($configPagination);
        $this->_view->Item = $this->_model->listItems($this->_arrParam)['0'];
        $this->_view->render("{$this->_controller}/detail");
    }

    // book detail 
    public function bookDetailAction()
    {
        $change = $this->_model->bookChangeCart($this->_arrParam);
        $result =  $this->_model->bookDetailCart($this->_arrParam, "id-book");
        if ($change) {
            echo (json_encode($result));
        } else {
            $array = ["status" => "0"];
            echo json_encode($array);
        }
    }

    // book In cart Html
    public function bookInCartHtmlAction()
    {
        $index = $_SESSION["index"];
        $arrBookInShop  = ["default" => "Select Book Name"];
        foreach ($this->_model->bookInShop() as $key => $value) {
            $arrBookInShop[$value["id"]] = $value["name"];
        }
        $selectbox = HTML::select1("form[book-id][]", null, null, $arrBookInShop, "default", null, null);
        $result = HTML::createBookInCartHTML("Book", $selectbox);
        echo json_encode(["HTML" => $result, "ses" => $_SESSION["index"]]);
    }
}
