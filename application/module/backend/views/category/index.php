<?php

$linkReload = URL::createLink($moduleName, $controllerName, $actionName);
$linkAddNew = URL::createLink($moduleName, $controllerName, 'form');
$xhtml = '';
$moduleName     = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = $this->arrParam['action'];
$filter_search    = $this->arrParam['filter_search'] ?? '';
foreach ($this->items as $item) {
    $checkbox       = Helper::showItemCheckbox($item['id']);
    $id             = Helper::highLight($item['id'], $filter_search);
    $name           = Helper::highLight($item['name'], $filter_search);
    $linkGroupACP   = URL::createLink($moduleName, $controllerName, 'ajaxChangeGroupACP', ['id' => $item['id'], 'group_acp' => $item['group_acp']]);
    $status      = Helper::showItemStatus($item['status'], URL::createLink($moduleName, $controllerName, 'ajaxStatus', ['id' => $id, 'status' => $item['status']]), $id);
    $ordering       = Helper::showItemOrdering($item['id'], $item['ordering']);
    $created        = Helper::showItemHistory($item['created_by'], $item['created']);
    $modified       = Helper::showItemHistory($item['modified_by'], $item['modified']);
    $actionButton   = Helper::showActionButton($moduleName, $controllerName, $item['id']);
    $picture        = Helper::showPicture('category', $item['picture'], "show-picture-" . $id);
    $changePicInput = Helper::HtmlFormPics($id, $this->arrParam['controller'], $this->arrParam['controller']);
    $xhtml .= '
    <tr>
        <td class="text-center">' . $checkbox . '</td>
        <td class="text-center">' . $id . '</td>
        <td class="text-center">' . $name . '</td>
        <td class="text-center">' . $picture . '</td>
        <td class="text-center">' . $changePicInput . '</td>
        <td class="text-center position-relative">' . $status . '</td>
        <td class="text-center position-relative">' . $ordering . '</td>
        <td class="text-center">' . $created . '</td>
        <td class="text-center modified-' . $item['id'] . '"">' . $modified . '</td>
        <td class="text-center">' . $actionButton . '</td>
    </tr>
    ';
}


$columnPost  = $this->arrParam['filter_column'];
$orderPost   = $this->arrParam['filter_column_dir'];

$lblID       = Helper::cmsLinkSort('ID', 'id', $columnPost, $orderPost);
$lblName     = Helper::cmsLinkSort('Name', 'name', $columnPost, $orderPost);
$lblStatus   = Helper::cmsLinkSort('Status', 'status', $columnPost, $orderPost);
$lblOrdering = Helper::cmsLinkSort('Ordering', 'ordering', $columnPost, $orderPost);
$lblCreated  = Helper::cmsLinkSort('Created', 'created', $columnPost, $orderPost);
$lblModified = Helper::cmsLinkSort('Modified', 'modified', $columnPost, $orderPost);

require_once 'html/search-filter.php'
?>


<!-- List -->
<div class="card card-info card-outline">
    <div class="card-header">
        <h4 class="card-title">Search & Filter</h4>
        <div class="card-tools">

            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <!-- Control -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
            <div class="mb-1">
                <select id="bulk-action" name="bulk-action" class="custom-select custom-select-sm mr-1" style="width: unset">
                    <option value="" selected="">Bulk Action</option>
                    <option value="delete">Multi Delete</option>
                    <option value="active">Multi Active</option>
                    <option value="inactive">Multi Inactive</option>
                </select> <button id="bulk-apply" class="btn btn-sm btn-info">Apply <span class="badge badge-pill badge-danger navbar-badge" style="display: none"></span></button>
            </div>
            <a href="<?= $linkAddNew ?>" class="btn btn-sm btn-info"><i class="fas fa-plus"></i> Add New</a>
        </div>
        <!-- List Content -->
        <form action="#" method="post" class="table-responsive" id="form-table">
            <table class="table table-bordered table-hover text-nowrap btn-table mb-0">
                <thead>
                    <tr>
                        <th class="text-center">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="check-all">
                                <label for="check-all" class="custom-control-label"></label>
                            </div>
                        </th>
                        <th class="text-center"><?php echo $lblID ?></th>
                        <th class="text-center"><?php echo $lblName ?></th>
                        <th class="text-center">Picture</th>
                        <th class="text-center">Change Picture</th>
                        <th class="text-center"><?php echo $lblStatus ?></th>
                        <th class="text-center"><?php echo $lblOrdering ?></th>
                        <th class="text-center"><?php echo $lblCreated ?></th>
                        <th class="text-center"><?php echo $lblModified ?></th>
                        <th class="text-center"><a href="#">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?= $xhtml; ?>
                </tbody>
            </table>
            <div>
                <input type="hidden" name="filter_column" value="name">
                <input type="hidden" name="filter_column_dir" value="asc">
            </div>
        </form>
    </div>
    <div class="card-footer clearfix">
        <?= $this->pagination->showPaginationAdmin(); ?>
    </div>
</div>