<?php

$moduleName     = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = $this->arrParam['action'];

$action         = isset($this->arrParam['id']) ? "form&id={$this->arrParam['id']}" : "form";


//===== BUTTON SAVE ======
$linkSave   = URL::createLink($moduleName, $controllerName, $action, ['type' => 'save']);
$buttonSave = Helper::button($linkSave, 'btn btn-sm btn-success mr-1', 'Save');

//===== BUTTON SAVE CLOSE ======
$linkSaveClose   = URL::createLink($moduleName, $controllerName, $action, ['type' => 'save-close']);
$buttonSaveClose = Helper::button($linkSaveClose, 'btn btn-sm btn-success mr-1', 'Save & Close');

//===== BUTTON SAVE NEW ======
$linkSaveNew   = URL::createLink($moduleName, $controllerName, $action, ['type' => 'save-new']);
$buttonSaveNew = Helper::button($linkSaveNew, 'btn btn-sm btn-success mr-1', 'Save & New');

//===== BUTTON CANCEL ======
$linkCancel   = URL::createLink($moduleName, $controllerName, 'index');
$buttonCancel = Helper::button($linkCancel, 'btn btn-sm btn-danger mr-1', 'Cancel');



// Input
$dataForm       = $this->arrParam['form'];

$nameImg = $dataForm['picture'];
$img = '';

if (isset($dataForm['id'])) {
    $img = ' <img style=" margin-left: 150px;" src="' . URL_PUBLIC . 'files/category/' . $nameImg . '" alt="your image" />';
}


$inputName = Helper::input('text', 'form[name]', 'name', $dataForm['name'], 'form-control form-control-sm');
$rowName   = Helper::row('Name', $inputName, true);

$inputOrdering = Helper::input('text', 'form[ordering]', 'ordering', $dataForm['ordering'], 'form-control form-control-sm');
$rowOrdering   = Helper::row('Ordering', $inputOrdering, true);

//$inputPicture = Helper::input('file', 'picture', 'admin-file-upload', $item['picture'], 'inputbox');

$inputPicture = Helper::input1('picture');

$rowPicture   = Helper::row('Picture', $inputPicture);

$inputToken     = Helper::input('hidden', 'form[token]', 'token', time());

$selectStatus = Helper::select('form[status]', 'custom-select custom-select-sm', ['default' => '- Select Status -', '1' => 'Active', '0' => 'Inactive'], $dataForm['status']);
$rowStatus    = Helper::row('Status', $selectStatus);


$inputID        = '';
$rowID          = '';
if (isset($this->arrParam['id'])) {
    $imageHidden = Helper::input('hidden', 'form[image-hidden]', 'image', $dataForm['picture'], 'form-control form-control-sm-2');
    $inputID    = Helper::input('text', 'form[id]', 'id', $dataForm['id'], 'form-control form-control-sm', 'readonly');
    $rowID      = Helper::row('ID', $inputID) . $imageHidden;
}

$rowName     = Helper::row('Name', $inputName, true);

?>

<?= $this->errors ?? '' ?>
<div class="card card-info card-outline">
    <div class="card-body">
        <form action="" method="post" class="mb-0" id="admin-form" enctype="multipart/form-data">
            <?= $rowID . $rowName . $rowOrdering . $rowStatus . $rowPicture . $img  ?>


            <?= $inputToken ?>


        </form>
        <div class="col-12 col-sm-8 offset-sm-2">
            <img src="" alt="preview image" id="admin-preview-image" style="display: none;width: 200px; max-width: 400px">
        </div>
    </div>
    <div class="card-footer">
        <div class="col-12 col-sm-8 offset-sm-2">
            <?= $buttonSave . $buttonSaveClose . $buttonSaveNew . $buttonCancel ?>
        </div>
    </div>
</div>