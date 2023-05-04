<?php
$moduleName     = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = $this->arrParam['action'];

$filter_search = $_GET['filter_search'];

$linkForm = URL::createLink($moduleName, $controllerName, 'form');

$xhtml = '';
foreach ($this->Items as $key => $value) {
	$linkResetPas = URL::createLink($moduleName, $controllerName, 'resetPassword', ['id' => $value['id']]);
	if ($this->arrParam['key'] == 'id' || $this->arrParam['key'] == 'all') {
		$id          = Helper::highLight($value['id'], $filter_search);
	} else {
		$id = $value['id'];
	}

	$linkDelete  = URL::createLink($moduleName, $controllerName, 'delete', ['id' => $id]);
	$linkEdit    = URL::createLink($moduleName, $controllerName, 'form', ['id' => $id]);
	$linkDetail = URL::createLink($moduleName, $controllerName, 'detail', ['id' => $id]);


	$status      = Helper::showItemStatus($value['status'], URL::createLink($moduleName, $controllerName, 'ajaxStatus', ['id' => $value['id'], 'status' => $value['status']]), $value['id']);

	$ordered_by   = $value['username'];
	$dateCreate    = Helper::formatDate(DATETIME_FORMAT, $value['date']);

	$userName  = Helper::highLight($value['username'], $filter_search);


	$xhtml .= '	<tr>
					<td class="text-center">
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" type="checkbox" id="checkbox-' . $id . '" name="checkbox[]" value="' . $id . '">
							<label for="checkbox-' . $id . '" class="custom-control-label"></label>
						</div>
					</td>
					<td class="text-center">' . $id . '</td>
					<td class="text-center position-relative">' . $userName . '</td>
					<td class="text-center position-relative">' . $status . '</td>
					
					<td class="text-center modified-' . $id . '">
						<p class="mb-0 history-by"><i class="far fa-user"></i> ' . $ordered_by . '</p>
						<p class="mb-0 history-time"><i class="far fa-clock"></i> ' . $dateCreate . '</p>
					</td>
					<td class="text-center">

						<a href="' . $linkEdit . '" class="rounded-circle btn btn-sm btn-info" title="Edit">
							<i class="fas fa-pencil-alt"></i>
						</a>

						<a href="' . $linkDelete . '" class="rounded-circle btn btn-sm btn-danger btn-delete-item" title="Delete">
							<i class="fas fa-trash-alt"></i>
						</a>
						<a href="' . $linkDetail . '" class="rounded-circle btn btn-sm btn-info" title="View">
						<i class="fas fa-search"></i>
						</a>
					</td>
				</tr>';
}

$columnPost  = $this->arrParam['filter_column'];
$orderPost   = $this->arrParam['filter_column_dir'];

$lblID       = Helper::cmsLinkSort('ID', 'id', $columnPost, $orderPost);
$lblUserName    = Helper::cmsLinkSort('UserName', 'username', $columnPost, $orderPost);
$lblStatus   = Helper::cmsLinkSort('Status', 'status', $columnPost, $orderPost);
$lblDate = Helper::cmsLinkSort('Date', 'date', $columnPost, $orderPost);


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
						<th class="text-center"><?php echo $lblUserName ?></th>
						<th class="text-center"><?php echo $lblStatus ?></th>
						<th class="text-center"><?php echo $lblDate ?></th>
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