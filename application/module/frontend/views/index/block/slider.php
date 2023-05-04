<!-- SLIDER  -->

<?php
$xhtml = '';
foreach ($this->slider as $value) {
    if ($value['status'] == 1) {
        $xhtml .= Helper::slider($value['picture'], $value['link']);
    }
};

?>
<section class="p-0 my-home-slider">
    <div class="slide-1 home-slider">

        <?php echo $xhtml ?>
        <!-- <div>
            <a href="" class="home text-center">
                <img style="width:1920px, height:718px" src=" <?php
                                                                // echo URL_IMAGES . 'banner1.gif'
                                                                ?>" alt="" class="bg-img blur-up lazyload">
            </a>
        </div>
        <div>
            <a href="" class="home text-center">
                <img style="width:1920px, height:718px" src="<?php
                                                                // echo URL_IMAGES . 'banner2.gif' 
                                                                ?>" alt="" class="bg-img blur-up lazyload">
            </a>
        </div>  -->
    </div>
</section><!-- END SLIDER  -->