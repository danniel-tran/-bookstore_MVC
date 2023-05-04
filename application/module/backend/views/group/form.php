<?php

$dataForm  = $this->arrParam['form'];

//===== ROW NAME ======
$inputName = Helper::input('text', 'form[name]', 'form[name]', $dataForm['name'], 'form-control form-control-sm');
$rowName   = Helper::row('Username', $inputName, true);


// ====== ROW ORDERING =======
$inputOrdering = Helper::input('number', 'form[ordering]', 'ordering', $dataForm['ordering'], 'form-control form-control-sm');
$rowOrdering   = Helper::row('Ordering', $inputOrdering, true);


//===== ROW STATUS ======
$sectionStatus = Helper::select('form[status]', 'custom-select custom-select-sm', ['default' => '- Select status -', 1 => 'Active', 0 => 'Inactive'], $dataForm['status']);
$rowStatus = Helper::row('Status', $sectionStatus, true);

//===== ROW GROUP ACP ======
$sectionGroupACP = Helper::select('form[group_acp]', 'custom-select custom-select-sm', ['default' => '- Select Group ACP -', 1 => 'Yes', 0 => 'No'], $dataForm['group_acp']);
$rowGroupACP = Helper::row('Group ACP', $sectionGroupACP, true);

//===== INPUT TOKEN ======
$inputToken = Helper::input('hidden', 'form[token]', 'form[token]', time(), null);

//===== BUTTON SAVE ======
$linkSave   = URL::createLink('backend', 'group', 'form', ['type' => 'save']);
$buttonSave = Helper::button($linkSave, 'btn btn-sm btn-success mr-1', 'Save');

//===== BUTTON SAVE CLOSE ======
$linkSaveClose   = URL::createLink('backend', 'group', 'form', ['type' => 'save-close']);
$buttonSaveClose = Helper::button($linkSaveClose, 'btn btn-sm btn-success mr-1', 'Save & Close');

//===== BUTTON SAVE NEW ======
$linkSaveNew   = URL::createLink('backend', 'group', 'form', ['type' => 'save-new']);
$buttonSaveNew = Helper::button($linkSaveNew, 'btn btn-sm btn-success mr-1', 'Save & New');

//===== BUTTON CANCEL ======
$linkCancel   = URL::createLink('backend', 'group', 'index');
$buttonCancel = Helper::button($linkCancel, 'btn btn-sm btn-danger mr-1', 'Cancel');

//===== STRING BUTTON ======
$strButton = $buttonSave . $buttonSaveClose . $buttonSaveNew . $buttonCancel;

//===== ROW ID  EDIT ======
if (isset($this->arrParam['id'])) {
    $inputID = Helper::input('text', 'form[id]', 'form[id]', $dataForm['id'], 'form-control form-control-sm ', 'readonly');
    $rowID   = Helper::row('ID', $inputID);
}


//!=================================================== END PHP ===================================================
?>

<section class="content">
    <div class="container-fluid">
        <?= $this->errors ?? '' ?>
        <div class="card card-info card-outline">
            <div class="card-body">
                <form action="" method="post" class="mb-0" id="admin-form">
                    <?= $rowName . $rowOrdering . $rowStatus . $rowGroupACP, $inputToken . $rowID ?>
                </form>
            </div>
            <div class="card-footer">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <?= $strButton ?>
                </div>
            </div>
        </div>
</section>