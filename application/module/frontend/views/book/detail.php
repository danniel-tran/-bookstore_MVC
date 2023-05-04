<?php
$xhtmlRelateBook = '';
foreach ($this->relateBook as $key => $value) {

    $xhtmlRelateBook .= '<div class="col-xl-2 col-md-4 col-sm-6">';
    $xhtmlRelateBook .= Helper::createRowProduct($value['name'], $value['sale_off'], $value['id'], $value['picture'], $value['price']);
    $xhtmlRelateBook .= '</div>';
}

$arrValue        = $this->infoBook;
$quantity        = $this->arrParam['quantity'];
$id              = $arrValue['id'];
$picture         = URL_UPLOAD . 'book' . DS . $arrValue['picture'];
$name            = $arrValue['name'];
$price           = ($arrValue['price']);
$newPrice        = number_format($price - ($price * $arrValue['sale_off'] / 100));
$newPrice1       = ($price - ($price * $arrValue['sale_off'] / 100));
$linkOrder       = URL::createLink('frontend', 'user', 'order', ['book_id' => $arrValue['id'], 'price' => $newPrice1]);
$price1           = number_format($arrValue['price']);
$saleOff         = $arrValue['sale_off'];
$sortDescription = $arrValue['short_description'];
$description     = $arrValue['description'];
$xhtml           = '
            <div class="col-lg-9 col-sm-12 col-xs-12">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="filter-main-btn mb-2"><span class="filter-btn"><i class="fa fa-filter" aria-hidden="true"></i> filter</span></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-xl-4">
                        <div>
                            <div>     
                                <div id="box-image">
                                    <img id="main-image" src="' . $picture . '" alt="" class="img-fluid w-100 blur-up lazyload image_zoom_cls-0" srcset="">
                                    <div id="result"></div>
                                </div>  
                                <div class="small-img">  
                                    <img src="' . URL_IMAGES . 'images/next-icon.png" class="icon-left" alt="" id="prev-img">
                                    <div class="small-container">
                                        <div id="small-img-roll" class = "item">
                                            <img src="' . URL_IMAGES . 'images/1.png" class="show-small-img" alt="">
                                            <img src="' . URL_IMAGES . 'images/2.png" class="show-small-img" alt="">
                                            <img src="' . URL_IMAGES . 'images/3.png" class="show-small-img" alt="">
                                            <img src="' . URL_IMAGES . 'images/4.png" class="show-small-img" alt="">
                                            <img src="' . URL_IMAGES . 'images/1.png" class="show-small-img" alt="">
                                            <img src="' . URL_IMAGES . 'images/2.png" class="show-small-img" alt="">
                                            <img src="' . URL_IMAGES . 'images/3.png" class="show-small-img" alt="">
                                            <img src="' . URL_IMAGES . 'images/4.png" class="show-small-img" alt="">
                                        </div>
                                    </div>    
                                    <img src="' . URL_IMAGES . 'images/next-icon.png" class="icon-right" alt="" id="next-img">              
                                </div>                  
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-xl-8 rtl-text">
                        <div class="product-right">
                            <h2 class="mb-2">' . $name . '</h2>
                            <h4><del>' . $price1 . ' đ</del><span> -' . $saleOff . '%</span></h4>
                            <h3 class="price">' . $newPrice . ' đ</h3>
                            <div class="product-description border-product">
                                <h6 class="product-title">Số lượng</h6>
                                    <div class="qty-box">
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <button type="button" class="btn quantity-left-minus" data-type="minus" data-field="">
                                                <i class="ti-angle-left"></i>
                                            </button>
                                        </span>
                                        <input type="text" name="quantity" class="quantity form-control input-number" value="1" data-id="' . $id . '">
                                        <span class="input-group-prepend">
                                            <button type="button" class="btn quantity-right-plus" data-type="plus" data-field="">
                                                <i class="ti-angle-right"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="product-buttons">
                                <a href="javascript:orderBook(\'' . $linkOrder . '\')" class="btn btn-buy btn-solid ml-0"><i class="fa fa-cart-plus"></i> Chọn mua</a>
                            </div>
                            <div class="border-product">' . $sortDescription . '</div>
                        </div>
                    </div>
                </div>
            </div>
            <section class="tab-product m-0">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 col-lg-12">
                            <ul class="nav nav-tabs nav-material" id="top-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-toggle="tab" href="#top-home" role="tab" aria-selected="true">Mô tả sản phẩm</a>
                                    <div class="material-border"></div>
                                </li>
                            </ul>
                            <div class="tab-content nav-material" id="top-tabContent">
                                <div class="tab-pane fade show active ckeditor-content" id="top-home" role="tabpanel" aria-labelledby="top-home-tab">
                                ' . $description  . '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
            ';
$bookName = $this->infoBook['name'];
$xhtmlNewbook1 = Helper::createSpecialBook($this->newBook1);
$xhtmlNewbook2 = Helper::createSpecialBook($this->newBook2);
$xhtmlSpecial1 = Helper::createSpecialBook($this->specialBook1);
$xhtmlSpecial2 = Helper::createSpecialBook($this->specialBook2);
?>
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2"><?php echo $bookName ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="section-b-space">
    <div class="collection-wrapper">
        <div class="container">
            <div class="row">
                <?php echo $xhtml ?>

                <?php require_once 'elements/rightside.php' ?>
            </div>
            <p> Bình luận về sách </p>
            <?php require_once 'elements/related.php' ?>
        </div>
    </div>
</section>