<?php
class BookController extends Controller
{

    //===== CONSTRUCT ======
    public function __construct($arrParams)
    {
        parent::__construct($arrParams);
        $this->_templateObj->setFolderTemplate('admin/theme_admin/');
        $this->_templateObj->setFileTemplate('index.php');
        $this->_templateObj->setFileConfig('template.ini');
        $this->_templateObj->load();
        //  $this->_moduleName = $this->_arrParam['module'];
        //  $this->_controllerName = $this->_arrParam['controller'];
    }

    //===== INDEX ACTION ======
    public function indexAction()
    {
        $this->_view->_title = ucfirst($this->_controller) . ' Manager :: List';

        $totalItems = $this->_model->countItems($this->_arrParam);
        $configPagination = ['totalItemsPerPage'    => 5, 'pageRange' => 3];
        $this->setPagination($configPagination);
        $this->_view->pagination        = new Pagination($totalItems, $this->_pagination);
        $this->_view->countActive       = $this->_model->countItems($this->_arrParam, ['task' => 'count-active']);
        $this->_view->countInactive     = $this->_model->countItems($this->_arrParam, ['task' => 'count-inactive']);
        $this->_view->slbGroup          = $this->_model->itemInSelectBox($this->_arrParam);
        $this->_view->slbFilterCategory = $this->_model->itemInSelectBox($this->_arrParam, 'add-default');
        $this->_view->items             = $this->_model->listItems($this->_arrParam);
        $this->_view->render("{$this->_controller}/index");
    }

    //===== FORM ACTION ======
    public function formAction()
    {
        if (!empty($_FILES['picture']['name'])) {
            $this->_arrParam['form']['picture'] = $_FILES['picture'];
        }
        $this->_validate->addSourceElement('picture',  $_FILES['picture']);

        $this->_view->_title = ucfirst($this->_controller) . ' Manager :: Add';
        if (isset($this->_arrParam['form']['token'])) {
            if (!empty($_FILES["upThumbFile"])) {
                $this->_arrParam['form']["upThumbFile"] = $_FILES["upThumbFile"] ?? [];
                foreach ($this->_arrParam['form']["upThumbFile"]["name"] as $key => $value) {
                    if (
                        empty($value)
                        && !isset($this->_arrParam['form']["thumb-name"][$key])
                        && $this->_arrParam['form']["thumbOrdering"][$key] == null
                    ) {
                        foreach ($this->_arrParam['form']["upThumbFile"] as $prop => $val) {
                            array_splice($this->_arrParam['form']["upThumbFile"][$prop], $key, 1);
                        }
                        array_splice($this->_arrParam['form']["thumbOrdering"], $key, 1);
                    }
                    if (empty($value) && isset($this->_arrParam['form']["thumb-name"][$key])) {
                        $this->_arrParam['form']["upThumbFile"]["name"][$key] = $this->_arrParam['form']["thumb-name"][$key];
                    }
                }
                if (empty($this->_arrParam['form']["upThumbFile"]["name"])) {
                    $this->_arrParam['form']["upThumbFile"] = array();
                }
            }

            $this->_validate->addSourceElement('upThumbFile', $this->_arrParam['form']["upThumbFile"]);
            $this->_validate->addSourceElement('thumbOrdering', $this->_arrParam['form']["thumbOrdering"]);


            if (isset($this->_arrParam['id']) && !isset($this->_arrParam['form']['picture']['name'])) {
                $this->_arrParam['form']['picture']['name'] = $this->_arrParam['form']['image-hidden'];
                $this->_validate->addSourceElement('picture',   $this->_arrParam['form']['picture']);
            }
            $this->_validate->validateThumb($this->_model);
            if (!$this->_validate->isValid()) {
                $this->_view->errors = $this->_validate->showErrorsAdmin();
            } else {
                $task = isset($this->_arrParam['id']) ? 'edit' : 'add';
                $id = $this->_model->saveItem($this->_arrParam, ['task' => $task]);
                $this->redirectAfterSave(['id' => $id]);
            }
        }
        if (isset($this->_arrParam['id'])) {
            $this->_view->_title = ucfirst($this->_controller) . ' Book :: Edit';
            $this->_arrParam['form'] = $this->_model->infoItem($this->_arrParam);
            if (empty($this->_arrParam['form'])) URL::redirect($this->_module, $this->_controller, 'index');
        }
        $this->_view->arrParam = $this->_arrParam;
        $this->_view->slbCategory = $this->_model->itemInSelectBox($this->_arrParam, 'add-default');
        $this->_view->render($this->_controller . "/form");
    }

    //===== AJAX CHANGE CATEGORY ACTION ======
    public function ajaxChangeCategoryAction()
    {
        $result = $this->_model->changeCategory($this->_arrParam);
        echo json_encode($result);
    }

    //===== AJAX CHANGE SPECIAL ACTION ======
    public function ajaxSpecialAction()
    {

        $result = $this->_model->changeStatus($this->_arrParam, ['task' => 'ajax_special']);
        echo json_encode($result);
    }

    //===== AJAX CHANGE STATUS ACTION ======
    public function ajaxStatusAction()
    {
        $result = $this->_model->changeStatus($this->_arrParam, ['task' => 'ajax_status']);
        echo json_encode($result);
    }

    //===== RESET PASSWORD ACTION ======
    public function resetPasswordAction()
    {
        $this->_view->_title = ucfirst($this->_controller) . ' Manager :: Reset Password';
        if (isset($this->_arrParam['new-password'])) {
            $this->_model->resetPassword($this->_arrParam);
            URL::redirect($this->_arrParam['module'], $this->_controller, 'index');
        }
        $this->_view->item = $this->_model->infoItem($this->_arrParam);
        $this->_view->render("{$this->_controller}/reset-password");
    }

    //===== CHANGE STATUS ACTIVE ======
    public function changeStatusAction()
    {
        $this->_model->changeStatus($this->_arrParam);
        URL::redirect($this->_module, $this->_controller, 'index');
    }

    //===== AJAX ORDERING ACTION ======
    public function ajaxOrderingAction()
    {
        $result = $this->_model->ajaxOrdering($this->_arrParam);
        echo json_encode($result);
    }

    //===== ACTIVE ACTION  ======
    public function activeAction()
    {
        $this->_model->changeStatus($this->_arrParam, ['task' => 'active']);
        URL::redirect($this->_module, $this->_controller, 'index');
    }

    //===== INACTIVE ACTION  ======
    public function inactiveAction()
    {
        $this->_model->changeStatus($this->_arrParam, ['task' => 'inactive']);
        URL::redirect($this->_module, $this->_controller, 'index');
    }

    //===== DELETE ACTION ======
    public function deleteAction()
    {
        $this->_model->deleteItems($this->_arrParam);
        URL::redirect($this->_module, $this->_controller, 'index');
    }
}
