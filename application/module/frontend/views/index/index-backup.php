<?php

//===== SAN PHAM NOI BAT ======
foreach ($this->Items as $key => $value) {
    $name     = $value['name'];
    $sale_off = $value['sale_off'];
    $id       = $value['id'];
    $picture  = $value['picture'];
    $price    = $value['price'];
    $xhtmlSanPhamNoiBat .= Helper::createRowProduct($name, $sale_off, $id, $picture, $price);
} //===== SAN PHAM NOI BAT ======


//===== TIEU DE DANH MUC NO BAT======
$xhtml = '';
foreach ($this->Items1 as $key => $value) {
    if ($key == 0) {
        $xhtml .= Helper::tieuDeDanhMucNoiBat('current', $value['id'], '1', $value['name']);
    } else {
        $xhtml .= Helper::tieuDeDanhMucNoiBat('',  $value['id'], '1', $value['name']);
    }
} //=====END TIEU DE DANH MUC NO BAT ======



//===== DANH MUC NOI BAT ======
$arrBooks = [];
$arrBook = $this->specialCategory;
$i = 0;
$j = 0;
$xhtmlTabTitle = "";

foreach ($arrBook as $key => $value) {
    $arrBooks[$i]['name'] = $value['name'];
    $arrBooks[$i]['id'] = $value['id'];
    foreach ($this->bookInCategory as $keyBook => $book) {
        if ($book['category_id'] == $value['id']) {
            $arrBooks[$i]['books'][$j] = $book;
        }
        $j++;
    }
    $i++;
}
$xhtmlBookInCategory = '';
echo '<pre>';
print_r($arrBooks);
echo '</pre>';
foreach ($arrBooks as $key => $value) {
    $linkList = URL::createLink('frontend', 'book', 'list', ['category_id' => $value['id']]);
    if ($key == 0) {
        $classBook = 'active default';
    } else {
        $classBook = '';
    }
    $xhtmlBookInCategory .= '
        <div id="tab-category-' . $value['id'] . '" class="tab-content ' . $classBook . '">
            <div class="no-slider row tab-content-inside">
    ';
    if (is_array($value['books']) || is_object($value['books'])) {
        foreach ($value['books'] as  $valueB) {
            $xhtmlBookInCategory .= Helper::createRowProduct($valueB['name'], $valueB['sale_off'], $valueB['id'], $valueB['picture'], $valueB['price']);
        }
    }
    $xhtmlBookInCategory .= '
                </div>
            <div class="text-center"><a href="' . $linkList . '" class="btn btn-solid">Xem tất cả</a></div>
        </div>     
    ';
} //=====END DANH MUC NOI BAT ======



//!================================================================================================================
?>
<?php require_once 'block/slider.php' ?>

<!-- SAN PHAM NOI BAT -->
<div class="title1 section-t-space title5">
    <h2 class="title-inner1">Sản phẩm nổi bật</h2>
    <hr role="tournament6">
</div>
<section class="section-b-space p-t-0 j-box ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="product-4 product-m no-arrow">
                    <?php echo $xhtmlSanPhamNoiBat ?>;
                </div>
            </div>
        </div>
    </div>
</section><!-- END SAN PHAM NOI BAT -->


<?php require_once 'block/giaohang_dichvu_uudai.php' ?>



<!-- DANH MUC NOI BAT-->
<div class="title1 section-t-space title5">
    <h2 class="title-inner1">Danh mục nổi bật</h2>
    <hr role="tournament6">
</div>
<section class="p-t-0 j-box ratio_asos">
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="theme-tab">
                    <ul class="tabs tab-title">
                        <?php echo $xhtml; ?>
                    </ul>
                    <div class="tab-content-cls">
                        <?php echo $xhtmlBookInCategory ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--END DANH MUC NOI BAT-->


<!-- Quick-view modal popup start-->
<div class="modal fade bd-example-modal-lg theme-modal" id="quick-view" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content quick-view-modal">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">X</span></button>
                <div class="row">
                    <div class="col-lg-6 col-xs-12">
                        <div class="quick-view-img"><img style="width:200px" src="" alt="" class="w-100 img-fluid blur-up lazyload book-picture"></div>
                    </div>
                    <div class="col-lg-6 rtl-text">
                        <div class="product-right">
                            <h2 class="book-name"></h2>
                            <h3 class="book-price"><span class="sale-price"></span> <del></del></h3>
                            <div class="border-product">
                                <div class="book-description"></div>
                            </div>
                            <div class="product-description border-product">
                                <h6 class="product-title">Số lượng</h6>
                                <div class="qty-box">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <button type="button" class="btn quantity-left-minus" data-type="minus" data-field="">
                                                <i class="ti-angle-left"></i>
                                            </button>
                                        </span>
                                        <input type="text" name="quantity" class="form-control input-number" value="1">
                                        <span class="input-group-prepend">
                                            <button type="button" class="btn quantity-right-plus" data-type="plus" data-field="">
                                                <i class="ti-angle-right"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-buttons">
                                <a href="#" class="btn btn-solid mb-1 btn-add-to-cart">Chọn Mua</a>
                                <a href="" class="btn btn-solid mb-1 btn-view-book-detail">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- Quick-view modal popup end-->