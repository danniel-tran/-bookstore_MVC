<?php
$moduleName   = $this->arrParam['module'];
$linkGroup    = URL::createLink($moduleName, 'group', 'index');
$linkUser     = URL::createLink($moduleName, 'user', 'index');
$linkCategory = URL::createLink($moduleName, 'category', 'index');
$linkBook     = URL::createLink($moduleName, 'book', 'index');
$linkSlider    = URL::createLink($moduleName, 'slider', 'index');

$listSmallBox = [
    Helper::showBoxDashboard('Group', $this->countGroup, $linkGroup, 'bg-info', 'ion-ios-people'),
    Helper::showBoxDashboard('User', $this->countUser, $linkUser, 'bg-success', 'ion ion-ios-person'),
    Helper::showBoxDashboard('Category', $this->countCategory, $linkCategory, 'bg-warning', 'ion ion-stats-bars'),
    Helper::showBoxDashboard('Book', $this->countBook, $linkBook, 'bg-danger', 'ion ion-pie-graph'),
    Helper::showBoxDashboard('Slider', $this->countSlider, $linkSlider, 'bg-primary', 'ion ion-pie-graph'),
];

$xhtmlSmallBox = '';
foreach ($listSmallBox as $box) {
    $xhtmlSmallBox .= '<div class="col-lg-3 col-6">' . $box . '</div>';
}
//!=================================================== END PHP =======================================================
?>

<div class="row justify-content-center">
    <?= $xhtmlSmallBox; ?>
</div>