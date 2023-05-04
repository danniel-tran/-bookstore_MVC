<?php
$moduleName    = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = $this->arrParam['action'];

$filter_search = $_GET['filter_search'];


$linkForm = URL::createLink($moduleName, $controllerName, 'form');

require_once 'html/search-filter.php';

$xhtml = '';

foreach ($this->Items as $key => $value) {

	if ($this->arrParam['key'] == 'id' || $this->arrParam['key'] == 'default') {
		$id          = Helper::highLight($value['id'], $filter_search);
	} else {
		$id = $value['id'];
	}
	$linkDelete  = URL::createLink($moduleName, $controllerName, 'delete', ['id' => $id]);
	$linkEdit    = URL::createLink($moduleName, $controllerName, 'form', ['id' => $id]);
	if ($this->arrParam['key'] == 'info' || $this->arrParam['key'] == 'default') {
		$arrInfo        = [
			['name' => 'Name',  'value' => Helper::highLight($value['name'], $filter_search)],
			['name' => 'Description',  'value' => Helper::highLight($value['description'], $filter_search)],
			['name' => 'Link',     'value' => Helper::highLight($value['link'], $filter_search)]
		];
	} else {
		$arrInfo        = [
			['name' => 'Name',  'value' => $value['name']],
			['name' => 'Description',  'value' => $value['description']],
			['name' => 'Link',     'value' => $value['link']]
		];
	}

	$info           = Helper::showUserInfo($arrInfo);


	if ($this->arrParam['key'] == 'name' || $this->arrParam['key'] == 'default') {
		$name        = Helper::highLight($value['name'], $filter_search);
	} else {
		$name = $value['name'];
	}
	$status      = Helper::showItemStatus($value['status'], URL::createLink('backend', 'slider', 'ajaxStatus', ['id' => $id, 'status' => $value['status']]), $id);

	$ordering    = Helper::showItemOrdering($value['id'], $value['ordering']);
	$created     = Helper::formatDate(DATETIME_FORMAT, $value['created']);
	$created_by  = $value['created_by'];
	$modified    = Helper::formatDate(DATETIME_FORMAT, $value['modified']);
	$modified_by = $value['modified_by'];

	$picture = 	Helper::showPicture('slider', $value['picture'], "show-picture-" . $id);
	$class = ($value['id'] == 1) ? 'class = "my-read-only"' : '';
	$changePicInput = Helper::HtmlFormPics($id);
	$xhtml .= '<tr ' . $class . '>
					<td class="text-center">
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" type="checkbox" id="checkbox-' . $id . '" name="checkbox[]" value="' . $id . '">
							<label for="checkbox-' . $id . '" class="custom-control-label"></label>
						</div>
					</td>
					<td class="text-center">' . $id . '</td>
					<td width:25% class="text-left text-wrap"  >' . $info . '</td>
					<td class="text-center position-relative">' . $picture . '</td>
					<td class="">' . $changePicInput . ' </td>
					<td class="text-center position-relative">' . $status . '</td>
					<td class="text-center position-relative">' . $ordering . '</td>
					<td class="text-center">
						<p class=" mb-0 history-by"><i class="text-dark far fa-user"></i> ' . $created_by . '</p>
						<p class="mb-0 history-time"><i class="far fa-clock"></i> ' . $created . '</p>
					</td>
					<td class="text-center modified-' . $id . '">
						<p class="	mb-0 history-by"><i class="far fa-user"></i> ' . $modified_by . '</p>
						<p class="mb-0 history-time"><i class="far fa-clock"></i> ' . $modified . '</p>
					</td>
					<td class="text-center">
						<a href="' . $linkEdit . '" class="rounded-circle btn btn-sm btn-info" title="Edit">
							<i class="fas fa-pencil-alt"></i>
						</a>

						<a href="' . $linkDelete . '" class="rounded-circle btn btn-sm btn-danger btn-delete-item" title="Delete">
							<i class="fas fa-trash-alt"></i>
						</a>
					</td>
				</tr>';
};
$columnPost  = $this->arrParam['filter_column'];
$orderPost   = $this->arrParam['filter_column_dir'];

$lblID       = Helper::cmsLinkSort('ID', 'id', $columnPost, $orderPost);
$lblName     = Helper::cmsLinkSort('Name', 'name', $columnPost, $orderPost);
$lblStatus   = Helper::cmsLinkSort('Status', 'status', $columnPost, $orderPost);

$lblOrdering = Helper::cmsLinkSort('Ordering', 'ordering', $columnPost, $orderPost);
$lblCreated  = Helper::cmsLinkSort('Created', 'created', $columnPost, $orderPost);
$lblModified = Helper::cmsLinkSort('Modified', 'modified', $columnPost, $orderPost);
$lblChangePicture = '<a>' . "Change Picture" . '</a>';




//!=========================================== END PHP ===========================================
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
		<form action="#" method="post" class="table-responsive" id="form-table" enctype="multipart/form-data">
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
						<th class="text-center"><?php echo $lblChangePicture ?></th>
						<th class="text-center"><?php echo $lblStatus ?></th>

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
				<!-- <input type="hidden" name="picture-upload-hidden" value=""> -->
				<!-- <input type="hidden" name="image-ID" > -->
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