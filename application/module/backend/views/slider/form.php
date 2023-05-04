<?php
$dataForm  = $this->arrParam['form'];



//===== ROW NAME ======
$inputName = Helper::input('text', 'form[name]', 'form[name]', $dataForm['name'], 'form-control form-control-sm');
$rowName   = Helper::row('Name', $inputName, true);

//===== ROW DESCRIPTION ======
$inputDescription = Helper::textarea(4, 103, 'form[description]', 'admin-form', $dataForm['description']);
$rowDescription   = Helper::row('Description', $inputDescription, true);

//===== ROW LINK ======
$inputLink = Helper::input('text', 'form[link]', 'link', $dataForm['link'], 'form-control form-control-sm');
$rowLink   = Helper::row('Link', $inputLink, true);

//===== ROW STATUS ======
$sectionStatus = Helper::select('form[status]', 'custom-select custom-select-sm', ['default' => '- Select status -', 1 => 'Active', 0 => 'Inactive'], $dataForm['status']);
$rowStatus = Helper::row('Status', $sectionStatus, true);
//===== ROW ORDERING ======
// $sectionOrdering = Helper::showItemOrdering($value['id'], $value['ordering']);
$sectionOrdering = Helper::input('text', 'form[ordering]', 'form[ordering]', $dataForm['ordering'], 'form-control form-control-sm');
$rowOrdering = Helper::row('Ordering', $sectionOrdering, true);

//===== INPUT TOKEN ======
$inputToken = Helper::input('hidden', 'form[token]', 'form[token]', time(), null);

//===== BUTTON SAVE ======
$linkSave   = URL::createLink('backend', 'slider', 'form', ['type' => 'save']);
$buttonSave = Helper::button($linkSave, 'btn btn-sm btn-success mr-1', 'Save');

//===== BUTTON SAVE CLOSE ======
$linkSaveClose   = URL::createLink('backend', 'slider', 'form', ['type' => 'save-close']);
$buttonSaveClose = Helper::button($linkSaveClose, 'btn btn-sm btn-success mr-1', 'Save & Close');

//===== BUTTON SAVE NEW ======
$linkSaveNew   = URL::createLink('backend', 'slider', 'form', ['type' => 'save-new']);
$buttonSaveNew = Helper::button($linkSaveNew, 'btn btn-sm btn-success mr-1', 'Save & New');


//===== BUTTON CANCEL ======
$linkCancel   = URL::createLink('backend', 'group', 'index');
$buttonCancel = Helper::button($linkCancel, 'btn btn-sm btn-danger mr-1', 'Cancel');


//===== STRING BUTTON ======
$strButton = $buttonSave . $buttonSaveClose . $buttonSaveNew . $buttonCancel;

//Image
$fileUpload = $dataForm['picture'];
$link = URL_UPLOAD . 'slider' . DS . $fileUpload;
$picture = Helper::cmsImage($link);

//Input Picture
$inputPicture = Helper::cmsInputFile('picture', $picture);
$inputPictureHidden = '';
//===== ROW ID  EDIT ======
if (isset($this->arrParam['id'])) {
    $inputID = Helper::input('text', 'form[id]', 'form[id]', $dataForm['id'], 'form-control form-control-sm ', 'readonly');
    $rowID   = Helper::row('ID', $inputID);
    //image
    $inputPictureHidden    = Helper::cmsInput('hidden', 'form[picture-hidden]', 'picture-hidden', $dataForm['picture'], 'inputbox', 40);
};

// Row Picture


$rowPicture   = Helper::row('Picture', $inputPicture . $inputPictureHidden);






//!=================================================== END PHP ===================================================
?>

<section class="content">
    <div class="container-fluid">
        <?= $this->errors ?? '' ?>
        <div class="card card-info card-outline">
            <div class="card-body">
                <form action="" method="post" class="mb-0" id="admin-form" enctype="multipart/form-data">
                    <?= $rowName . $rowDescription . $rowLink . $rowStatus . $rowOrdering . $inputToken . $rowPicture . $rowID ?>
                </form>
            </div>
            <div class="card-footer">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <?= $strButton ?>
                </div>
            </div>
        </div>
</section>