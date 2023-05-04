<?php
$moduleName     = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = isset($this->arrParam['id']) ? "form&id={$this->arrParam['id']}" : "form";


$dataForm  = $this->arrParam['form'];

$inputName = Helper::input('text', 'form[username]', 'form[username]', $dataForm['username'], 'form-control form-control-sm');
$rowName   = Helper::row('Username', $inputName, true);


$sectionStatus = Helper::select('form[status]', 'custom-select custom-select-sm', ['default' => '- Select status -', 1 => 'Active', 0 => 'Inactive'], $dataForm['status']);
$rowStatus = Helper::row('Status', $sectionStatus, true);

$dataFortmat = !empty($dataForm['date']) ? date('Y-m-d\TH:i', strtotime($dataForm['date'])) : "";
$dateInput = Helper::dateTimeInput("form[dateOrder]", $dataFortmat);
$rowDate = Helper::row("Date", $dateInput);

$inputToken = Helper::input('hidden', 'form[token]', 'token', time(),null,null,null);



// BUTTON
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
} else {
    $inputPassword  = Helper::input('password', 'form[password]', 'password', $dataForm['password'], 'form-control form-control-sm');
    $rowPassword    = Helper::row('Password', $inputPassword, true);
}

// =====================ADD NEW BOOK IN CART

$buttonAddBook = HTML::buttonBookCart("divNewBookInCart", "+ Add Book", "add-book-in-cart");
// JSON DECODE BOOK
$bookArrID = json_decode($dataForm["books"]) ?? [];
$bookQuans = json_decode($dataForm["quantities"]);
// SELECT BOX
$arrBookInShop  = ["default" => "Select Book Name"] ?? [];
$arrBookInCart = $this->arrBookInCart ?? [];
foreach ($arrBookInCart as $key => $value) {
    $arrBookInShop[$value["id"]] = $value["name"];
}
$containerBookCart = '<div id="cart-container" >';
foreach ($bookArrID as $key => $value) {
    $selectbox = HTML::select1("form[book-id][]", null, null, $arrBookInShop, $value, null, null);
    $bookInCartHtml = HTML::createBookInCartHTML("Book", $selectbox, $bookQuans[$key]);
    $containerBookCart .= $bookInCartHtml;
}
$containerBookCart .= '</div>';



//!=================================================== END PHP ===================================================
?>

<section class="content">
    <div class="container-fluid">
        <?= $this->errors ?? '' ?>
        <div class="card card-info card-outline">
            <div class="card-body">
                <form action="" method="post" class="mb-0" id="admin-form">
                    <?php echo $rowName . $rowStatus . $rowDate . $rowID . $buttonAddBook . $containerBookCart  ?>
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