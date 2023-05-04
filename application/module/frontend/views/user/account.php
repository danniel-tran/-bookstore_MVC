<?php
$arrMenu = [
    ['Change Password', 'changepass.png', URL::createLink('frontend', 'user', 'changePass')],
    ['View Cart', 'viewcart.jpg', URL::createLink('frontend', 'user', 'cart')],
    ['History', 'history.png', URL::createLink('frontend', 'user', 'history')],
    ['Logout', 'logout.jpg', URL::createLink('frontend', 'index', 'logout')]
];
$xhtml = '';
$dirFile = '/bookstoreClass/public/files/user/';
foreach ($arrMenu as $key => $value) {
    $xhtml .= '
    <div class="pic-wrapper" style="width:150px">
        <div class="img-pic">
            <div><a href="'.$value[2].'">
                    <h5 sytle="color:black;font-weight:bold;text-align:center">'.$value[0].'</h5>
                </a></div>
            <a href="'.$value[2].'">
                <img src="' . $dirFile .$value[1]. '" style="width:150px; height:200px">
            </a>
        </div>
    </div>
';
};
?>

<div class="breadcrumb-section" style="margin-top: 100px;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title">
                    <h2 class="py-2">My Account</h2>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="box-account" style="margin-left: 100px;">
    <?php echo $xhtml ?>
</div>