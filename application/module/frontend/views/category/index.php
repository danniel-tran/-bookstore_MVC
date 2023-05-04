<?php
// echo '<pre>';
// print_r($this);
// echo '</pre>';
$xhtml='';
foreach ($this->Items as $key => $value) {
    $link = '/bookstoreClass/index.php?module=frontend&controller=book&action=list'.'&bookCategory='.$value['id'];
    $xhtml .= Helper::showCategory($value['picture'], $value['name'],$link);
}
?>
    <div class="breadcrumb-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="page-title">
                        <h2 class="py-2">Danh mục sách</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="ratio_asos j-box pets-box section-b-space" id="category">
        <div class="container">
            <div class="no-slider five-product row">

            <?php
                echo $xhtml;
            ?>
            </div>

            <div class="product-pagination">
                <div class="theme-paggination-block">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-xl-6 col-md-6 col-sm-12">
                                <nav aria-label="Page navigation">
                                    <nav>
                                        <!-- <ul class="pagination">
                                            <li class="page-item disabled">
                                                <a href="" class="page-link"><i class="fa fa-angle-double-left"></i></a>
                                            </li>
                                            <li class="page-item disabled">
                                                <a href="" class="page-link"><i class="fa fa-angle-left"></i></a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#"><i class="fa fa-angle-right"></i></a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="#"><i class="fa fa-angle-double-right"></i></a>
                                            </li>
                                        </ul> -->
                                        <?= $this->pagination->showPaginationAdmin(); ?>
                                    </nav>
                                </nav>
                            </div>
                            <div class="col-xl-6 col-md-6 col-sm-12">
                                <div class="product-search-count-bottom">
                                    <h5>Showing Items 1-15 of 22 Result</h5>
                                    <?php 
                                    // echo $this->pagination->showPagination(); ?>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="phonering-alo-phone phonering-alo-green phonering-alo-show" id="phonering-alo-phoneIcon">
        <div class="phonering-alo-ph-circle"></div>
        <div class="phonering-alo-ph-circle-fill"></div>
        <a href="tel:0905744470" class="pps-btn-img" title="Liên hệ">
            <div class="phonering-alo-ph-img-circle"></div>
        </a>
    </div>

 