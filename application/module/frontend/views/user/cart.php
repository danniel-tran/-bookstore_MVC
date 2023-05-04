<?php
$xhtml = '';
$moduleName     = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = $this->arrParam['action'];
foreach ($this->bookOrder as $book) {

    $picture = Helper::cmsLinkTittle(URL::createLink($moduleName, 'book', 'detail', ['book_id' => $book['id']]), Helper::showPicture('book', $book['picture']));
    $name = Helper::highLight($book['name'], $filter_search);
    $unitPrice = $book['unit-price'];
    $quantity = $book['quantity'];
    $totalPrice = $book['total'];


    $linkGroupACP   = URL::createLink($moduleName, $controllerName, 'ajaxChangeGroupACP', ['id' => $item['id'], 'group_acp' => $item['group_acp']]);

    $inputBookID = Helper::input('hidden', 'form[book_id][]', 'input-book-id-' . $book['id'], $book['id']);
    $inputUnitPrice = Helper::input('hidden', 'form[price][]', 'input-unitPrice-' . $book['id'], $book['unit-price']);
    $inputQuantity = Helper::input('hidden', 'form[quantity][]', 'input-quantity-' . $book['id'], $book['quantity']);
    $inputName = Helper::input('hidden', 'form[name][]', 'input-name-' . $book['id'], $book['name']);
    $inputPicture = Helper::input('hidden', 'form[picture][]', 'input-picture-' . $book['id'], $book['picture']);
    $xhtml .= '
    <tr>
        <td class="text-center">' . $picture . '</td>
        <td class="text-center">' . $name . '</td>
        <td class="text-center">' . $unitPrice . '</td>
        <td class="text-center">' . $quantity . '</td>
        <td class="text-center">' . $totalPrice . '</td>
    </tr>
    ';
    $xhtml .= $inputBookID . $inputUnitPrice . $inputQuantity . $inputName . $inputPicture;
}


// ROW TITTLE
$lblPicture       = Helper::cmsLinkSort('Item picture', 'picture', $columnPost, $orderPost);
$lblName     = Helper::cmsLinkSort('Book name', 'name', $columnPost, $orderPost);
$lblUnitPrice   = Helper::cmsLinkSort('Unit Price', 'status', $columnPost, $orderPost);
$lblQuantity = Helper::cmsLinkSort('Quantity', 'ordering', $columnPost, $orderPost);
$lblTotal  = Helper::cmsLinkSort('Total', 'created', $columnPost, $orderPost);


$linkContinue = URL::createLink("frontend", "index", "index");
$linkCheckOut = URL::createLink("frontend", "user", "buy");


if (!empty($this->bookOrder)) {
?>
    <!-- List -->
    <div class="card card-info card-outline" style="top: 100px">
        <div class="card-body">
            <form action="#" method="post" class="table-responsive" id="form-table" style="margin-bottom: 100px">
                <table class="table table-bordered table-hover text-nowrap btn-table mb-0">
                    <thead>
                        <tr>
                            <th class="text-center"><?php echo $lblPicture ?></th>
                            <th class="text-center"><?php echo $lblName ?></th>
                            <th class="text-center"><?php echo $lblUnitPrice ?></th>
                            <th class="text-center"><?php echo $lblQuantity ?></th>
                            <th class="text-center"><?php echo $lblTotal ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?= $xhtml; ?>
                    </tbody>
                </table>
                <!-- <h3><a href="">Continue</a></h3>
                <h3><a href="">CheckOut</a></h3> -->
                <div class="cart-shopping">
                    <a id="continue" href="<?php echo $linkContinue ?>">Continue</a>
                    <div></div>
                    <a id="checkout" href="javascript:submitForm('<?php echo $linkCheckOut  ?>')">CheckOut</a>
                </div>
            </form>
        </div>
    </div>
<?php
} else {
?>
    <h1 style="margin-top: 100px;">No Item is picked in cart</h1>
<?php
}
?>