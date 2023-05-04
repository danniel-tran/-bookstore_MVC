<?php
$moduleName     = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = isset($this->arrParam['id']) ? "form&id={$this->arrParam['id']}" : "form";



$dataForm  = $this->arrParam['form'];

$inputName = Helper::input('text', 'form[username]', 'form[username]', $dataForm['username'], 'form-control form-control-sm');
$rowName   = Helper::row('Username', $inputName, true);

$inputFullName = Helper::input('text', 'form[fullname]', 'form[fullname]', $dataForm['fullname'], 'form-control form-control-sm');
$rowFullName   = Helper::row('FullName', $inputFullName);

$inputEmail = Helper::input('text', 'form[email]', 'form[email]', $dataForm['email'], 'form-control form-control-sm');
$rowEmail   = Helper::row('Email', $inputEmail, true);

$sectionStatus = Helper::select('form[status]', 'custom-select custom-select-sm', ['default' => '- Select status -', 1 => 'Active', 0 => 'Inactive'], $dataForm['status']);
$rowStatus = Helper::row('Status', $sectionStatus, true);

$groupID          = Helper::select('form[group_id]', 'custom-select custom-select-sm mr-1', $this->slbGroup, $dataForm['group_id'], '', 'data-id="' . $value['id'] . '"');
$rowGroupID = Helper::row('Group ID', $groupID, true);

$inputToken = Helper::input('hidden', 'form[token]', 'token', time());


$linkSave   = URL::createLink($moduleName, $controllerName, $actionName, ['type' => 'save']);
$buttonSave = Helper::button($linkSave, 'btn btn-sm btn-success mr-1', 'Save');

$linkSaveClose   = URL::createLink($moduleName, $controllerName, $actionName, ['type' => 'save-close']);
$buttonSaveClose = Helper::button($linkSaveClose, 'btn btn-sm btn-success mr-1', 'Save & Close');

$linkSaveNew   = URL::createLink($moduleName, $controllerName, $actionName, ['type' => 'save-new']);
$buttonSaveNew = Helper::button($linkSaveNew, 'btn btn-sm btn-success mr-1', 'Save & New');

$linkCancel   = URL::createLink($moduleName, $controllerName, 'index');
$buttonCancel = Helper::button($linkCancel, 'btn btn-sm btn-danger mr-1', 'Cancel');

$strButton = $buttonSave . $buttonSaveClose . $buttonSaveNew . $buttonCancel;


$inputID = '';
$rowID   = '';
if (isset($this->arrParam['id'])) {
    $inputID = Helper::input('text', 'form[id]', 'form[id]', $dataForm['id'], 'form-control form-control-sm ', 'readonly');
    $rowID     = Helper::row('ID', $inputID);
}else{
    $inputPassword  = Helper::input('password', 'form[password]', 'password', $dataForm['password'], 'form-control form-control-sm');
    $rowPassword    = Helper::row('Password', $inputPassword, true);
}


//!=================================================== END PHP ===================================================
?>

<section class="content">
    <div class="container-fluid">
        <?= $this->errors ?? '' ?>
        <div class="card card-info card-outline">
            <div class="card-body">
                <form action="" method="post" class="mb-0" id="admin-form">
                    <?php echo $rowName .$rowPassword. $rowFullName . $rowEmail . $rowStatus . $rowGroupID. $rowID ?>
                    <?php echo $inputToken ?>
                </form>
            </div>
            <div class="card-footer">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <?= $strButton ?>
                </div>
            </div>
        </div>
</section>