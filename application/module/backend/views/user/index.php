<?php
$moduleName     = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = $this->arrParam['action'];

$filter_search = $_GET['filter_search'];

$linkForm = URL::createLink($moduleName, $controllerName, 'form');


foreach ($this->Items as $key => $value) {
	$linkResetPas = URL::createLink($moduleName, $controllerName, 'resetPassword', ['id' => $value['id']]);
	if ($this->arrParam['key'] == 'id' || $this->arrParam['key'] == 'all') {
		$id          = Helper::highLight($value['id'], $filter_search);
	} else {
		$id = $value['id'];
	}

	$linkDelete  = URL::createLink($moduleName, $controllerName, 'delete', ['id' => $id]);
	$linkEdit    = URL::createLink($moduleName, $controllerName, 'form', ['id' => $id]);


	if ($this->arrParam['key'] == 'info' || $this->arrParam['key'] == 'all') {
		$arrInfo        = [
			['name' => 'Username',  'value' => Helper::highLight($value['username'], $filter_search)],
			['name' => 'Fullname',  'value' => Helper::highLight($value['fullname'], $filter_search)],
			['name' => 'Email',     'value' => Helper::highLight($value['email'], $filter_search)]
		];
	} else {
		$arrInfo        = [
			['name' => 'Username',  'value' => $value['username']],
			['name' => 'Fullname',  'value' => $value['fullname']],
			['name' => 'Email',     'value' => $value['email']]
		];
	}

	$info           = Helper::showUserInfo($arrInfo);

	$status      = Helper::showItemStatus($value['status'], URL::createLink($moduleName, $controllerName, 'ajaxStatus', ['id' => $value['id'], 'status' => $value['status']]), $value['id']);

	$group          = Helper::select('select-group', 'custom-select custom-select-sm mr-1', $this->slbGroup, $value['group_id'], 'width: unset', 'data-id="' . $value['id'] . '"');

	$ordering    = Helper::showItemOrdering($value['id'], $value['ordering']);
	$created     = Helper::formatDate(DATETIME_FORMAT, $value['created']);
	$created_by  = $value['created_by'];
	$modified    = Helper::formatDate(DATETIME_FORMAT, $value['modified']);
	$modified_by = $value['modified_by'];

	$xhtml .= '	<tr>
					<td class="text-center">
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" type="checkbox" id="checkbox-' . $id . '" name="checkbox[]" value="' . $id . '">
							<label for="checkbox-' . $id . '" class="custom-control-label"></label>
						</div>
					</td>
					<td class="text-center">' . $id . '</td>
					<td class="text-left">' . $info . '</td>
					<td class="text-center position-relative">' . $status . '</td>
					<td class="text-center position-relative">' . $group . '</td>
					<td class="text-center position-relative">' . $ordering . '</td>
					
					<td class="text-center">
						<p class="mb-0 history-by"><i class="color_red far fa-user"></i> ' . $created_by . '</p>
						<p class="mb-0 history-time"><i class="far fa-clock"></i> ' . $created . '</p>
					</td>
					<td class="text-center modified-' . $id . '">
						<p class="mb-0 history-by"><i class="far fa-user"></i> ' . $modified_by . '</p>
						<p class="mb-0 history-time"><i class="far fa-clock"></i> ' . $modified . '</p>
					</td>
					<td class="text-center">

						<a href="' . $linkResetPas . '" class="rounded-circle btn btn-sm btn-secondary" title="Reset Password">
							<i class="fas fa-key"></i>
						</a>
						<a href="' . $linkEdit . '" class="rounded-circle btn btn-sm btn-info" title="Edit">
							<i class="fas fa-pencil-alt"></i>
						</a>

						<a href="' . $linkDelete . '" class="rounded-circle btn btn-sm btn-danger btn-delete-item" title="Delete">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>';
}

$columnPost  = $this->arrParam['filter_column'];
$orderPost   = $this->arrParam['filter_column_dir'];

$lblID       = Helper::cmsLinkSort('ID', 'id', $columnPost, $orderPost);
//$lblName     = Helper::cmsLinkSort('Info', 'name', $columnPost, $orderPost);
$lblStatus   = Helper::cmsLinkSort('Status', 'status', $columnPost, $orderPost);
$lblGroup    = Helper::cmsLinkSort('Group ID', 'group_acp', $columnPost, $orderPost);
$lblOrdering = Helper::cmsLinkSort('Ordering', 'ordering', $columnPost, $orderPost);
$lblCreated  = Helper::cmsLinkSort('Created', 'created', $columnPost, $orderPost);
$lblModified = Helper::cmsLinkSort('Modified', 'modified', $columnPost, $orderPost);


require_once 'html/search-filter.php';
//!=========================================== END PHP ===================================================
?>
<!-- List -->
<div class="card card-info card-outline">

	<!-- HEADER -->
	<div class="card-header">
		<h4 class="card-title">List</h4>
		<div class="card-tools">
			<button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
				<i class="fas fa-minus"></i></button>
		</div>
	</div><!-- END HEADER -->

	<!-- BODY -->
	<div class="card-body">

		<!-- CONTROL -->
		<div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
			<div class="mb-1">
				<select id="bulk-action" name="bulk-action" class="custom-select custom-select-sm mr-1" style="width: unset">
					<option value="" selected="">Bulk Action</option>
					<option value="delete">Multi Delete</option>
					<option value="active">Multi Active</option>
					<option value="inactive">Multi Inactive</option>
				</select>
				<button id="bulk-apply" class="btn btn-sm btn-info">Apply
					<span class="badge badge-pill badge-danger navbar-badge" style="display: none"></span>
				</button>
			</div>
			<a href="<?= $linkForm ?>" class="btn btn-sm btn-info"><i class="fas fa-plus"></i> Add New</a>
		</div><!-- END CONTROL -->

		<!-- LIST CONTENT -->
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
						<th class="text-center">Info</th>
						<th class="text-center"><?php echo $lblStatus ?></th>
						<th class="text-center">Group ID</th>
						<th class="text-center"><?php echo $lblOrdering ?></th>
						<th class="text-center"><?php echo $lblCreated ?></th>
						<th class="text-center"><?php echo $lblModified ?></th>
						<th class="text-center"><a href="#">Action</th>
					</tr>
				</thead>
				<tbody>
					<?php echo $xhtml ?>
				</tbody>
			</table>
			<div>
				<input type="hidden" name="filter_column" value="name">
				<input type="hidden" name="filter_column_dir" value="asc">
			</div>
		</form><!-- END LIST CONTENT -->

	</div><!-- END BODY -->

	<!-- FOOTER -->
	<div class="card-footer clearfix">
		<ul class="pagination pagination-sm m-0 float-right">
			<?= $this->pagination->showPaginationAdmin() ?>
		</ul>
	</div><!-- END FOOTER -->

</div>