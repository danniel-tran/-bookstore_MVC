<?php
$moduleName            = $this->arrParam['module'];
$controllerName        = $this->arrParam['controller'];
$actionName            = $this->arrParam['action'];


$action                = isset($this->arrParam['id']) ? "form&id={$this->arrParam['id']}" : "form";

$dataForm              = $this->arrParam['form'];
$inputName = Helper::input('text', 'form[name]', 'form[name]', $dataForm['name'], 'form-control form-control-sm');
$rowName   = Helper::row('Name', $inputName, true);

$inputPrice            = Helper::input('number', 'form[price]', 'price', $dataForm['price'], 'form-control form-control-sm', null);
$rowPrice              = Helper::row('Price', $inputPrice);

$inputSale_off         = Helper::input('number', 'form[sale_off]', 'form[sale_off]', $dataForm['sale_off'], 'form-control form-control-sm');
$rowSale_off           = Helper::row('Sale Off', $inputSale_off);

$selectCategory        = Helper::select('form[category_id]', 'custom-select custom-select-sm', $this->slbCategory, $dataForm['category_id']);
$rowCategory           = Helper::row('Category', $selectCategory, true);

$selectStatus          = Helper::select('form[status]', 'custom-select custom-select-sm', ['default' => '- Select Status -', '1' => 'Active', '0' => 'Inactive'], $dataForm['status']);
$rowStatus             = Helper::row('Status', $selectStatus, true);

$selectSpecial         = Helper::select('form[special]', 'custom-select custom-select-sm', ['default' => '- Select Special -', '1' => 'Yes', '0' => 'No'], $dataForm['special']);
$rowSpecial            = Helper::row('Special', $selectSpecial, true);

$inputPicture          = Helper::input1('picture');
$rowPicture            = Helper::row('Picture', $inputPicture);

$inputToken            = Helper::input('hidden', 'form[token]', 'form[token]', time(), null, null, null);

$inputDescription = Helper::textarea(4, 103, 'form[description]', 'admin-form', $dataForm['description']);
$rowDescription   = Helper::row('Description', $inputDescription, true);


if (isset($dataForm['id'])) {
    $imageHidden = Helper::input('hidden', 'form[image-hidden]', 'image', $dataForm['picture'], 'form-control form-control-sm-2');
    $inputID    = Helper::input('text', 'form[id]', 'id', $dataForm['id'], 'form-control form-control-sm', 'readonly');
    $rowID      = Helper::row('ID', $inputID) . $imageHidden;

    // Image Show Edit
    $img = ' <img style=" margin-left: 150px;" src="' . URL_PUBLIC . 'files/book/' . $dataForm['picture'] . '" alt="your image" />';
}

// // ============********Row Thumnail*************====================
$buttonThumnail = Helper::buttonAddThumnail();
$thumbFile  = json_decode($dataForm["detail_picture"]) ??  [];
$orderThumb = json_decode($dataForm["order-thumb"]) ?? [];
// arrange
$thumbHtml = '<div class ="html-thumb">';
foreach ($thumbFile as $key => $value) {
    // input thumbnail
    $inputThumb = Helper::inputThumbnail("upThumbFile[]");
    $rowInputThumb = Helper::rowInputThumbnail($inputThumb);
    // input thumbnail ordering
    $inputThumbOrder  = Helper::inputThumbnailOrdering("form[thumbOrdering][]", $orderThumb[$key]);
    $rowInputOrder = Helper::rowInputOrderingThumbnail($inputThumbOrder, $value);
    // row-thumbnail
    $btnDelThumb = '<a class = "btn delt-thumb"><i class="far fa-trash-alt"></i></a>';
    $inputNameThumbHidden = Helper::input('hidden', 'form[thumb-name][]', null, $value, null, null, null);;
    $rowThumb = Helper::rowThumnail("Thumnail", $rowInputThumb . $rowInputOrder . $btnDelThumb . $inputNameThumbHidden);
    $thumbHtml .= Helper::HtmlThumb($rowThumb);
    //main-thumbnail-HTML

};
$thumbHtml .= '</div>';

//===== BUTTON SAVE ======
$linkSave   = URL::createLink($moduleName, $controllerName, $action, ['type' => 'save']);
$btnSave = Helper::button($linkSave, 'btn btn-sm btn-success mr-1', 'Save');

//===== BUTTON SAVE CLOSE ======
$linkSaveClose   = URL::createLink($moduleName, $controllerName, $action, ['type' => 'save-close']);
$btnSaveClose = Helper::button($linkSaveClose, 'btn btn-sm btn-success mr-1', 'Save & Close');

//===== BUTTON SAVE NEW ======
$linkSaveNew   = URL::createLink($moduleName, $controllerName, $action, ['type' => 'save-new']);
$btnSaveNew = Helper::button($linkSaveNew, 'btn btn-sm btn-success mr-1', 'Save & New');

//===== BUTTON CANCEL ======
$linkCancel   = URL::createLink($moduleName, $controllerName, 'index');
$btnCancel = Helper::button($linkCancel, 'btn btn-sm btn-success mr-1', 'Cancel');

//END *****
?>





<section class="content">
    <div class="container-fluid">

        <?= $this->errors ?? '' ?>
        <div class="card card-info card-outline">
            <div class="card-body">
                <form action="" method="post" class="mb-0" id="admin-form" enctype="multipart/form-data">
                    <?php
                    echo $rowID . $rowName . $rowDescription . $rowPrice . $rowSale_off . $rowCategory . $rowStatus . $rowSpecial . $rowPicture . $img . $inputToken . $buttonThumnail . $rowThumnail . $thumbHtml;
                    ?>

                </form>
            </div>
            <div class="card-footer">
                <div class="col-12 col-sm-8 offset-sm-2">
                    <?= $btnSave . $btnSaveClose . $btnSaveNew . $btnCancel ?>
                </div>
            </div>

        </div>
    </div>
</section>