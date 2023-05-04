<?php

$xhtml = '';
foreach ($this->historyItem as $key => $value) {
    $cartID = $value['id'];
    $arrBookID  = json_decode($value['books']);
    $time = date("H:i d/m/Y", strtotime($value['date']));
    $arrPicture = json_decode($value['pictures']);
    $arrName = json_decode($value['names']);
    $arrUnitPrice = json_decode($value['prices']);
    $arrQuantity = json_decode($value['quantities']);
    $tableContent = '';
    foreach ($arrName as $keyB => $valueB) {
        $link = URL::createLink('frontend','book','detail',['book_id'=>$arrBookID[$keyB]]);
        $tableContent .= ' 
        <tr>
            <td class="text-center"> <a href = "'.$link.'"><img src="' . URL_UPLOAD . 'book' . DS . $arrPicture[$keyB] . '" width="100" height="150"></a></td>
            <td class="text-center"> ' . $arrName[$keyB] . '</td>
            <td class="text-center"> ' . $arrUnitPrice[$keyB] . ' </td>
            <td class="text-center"> ' . $arrQuantity[$keyB] . ' </td>
            <td class="text-center"> ' . $arrUnitPrice[$keyB] * $arrQuantity[$keyB] . '</td>
        </tr>
    ';
    }
    $xhtml .= '
    <h4 style = "margin-top: 150px; font-size:30px; font: bold">Shipment Code: ' . $cartID . '- Order Time: ' . $time . '</h4>
    <table class="table table-bordered table-hover text-nowrap btn-table mb-0">
        <thead>
            <tr>
                <th class="text-center">Picture</th>
                <th class="text-center">Name</th>
                <th class="text-center">Unit Price</th>
                <th class="text-center">Quantity</th>
                <th class="text-center">Total</th>
            </tr>
        </thead>
        <tbody>
            ' . $tableContent . '
        </tbody>
    </table>';
};

?>
<!-- List -->
<div class="card card-info card-outline" style="top: 100px; margin-bottom: 100px;">
    <div class="card-body">
        <h1>History</h1>

        <?php echo $xhtml ?>
    </div>
</div>