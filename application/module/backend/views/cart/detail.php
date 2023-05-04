<?php
$moduleName     = $this->arrParam['module'];
$controllerName = $this->arrParam['controller'];
$actionName     = $this->arrParam['action'];

$arrBookInShop  = [];
foreach ($this->booksInShop as $key => $value) {
	$arrBookInShop[$value["id"]] = $value["name"];
}


$arrBookID = json_decode($this->Item['books']);
$arrPrice = json_decode($this->Item['prices']);
$arrQuantity = json_decode($this->Item['quantities']);
$arrBookName = json_decode($this->Item['names']);
$arrBookPicture = json_decode($this->Item['pictures']);


$xhtml = '';
$id = $this->Item['id'];
foreach ($arrBookID ?? [] as $key => $value) {
	$bookID = $value;
	$linkResetPas = URL::createLink($moduleName, $controllerName, 'resetPassword', ['id' => $id, 'book_id' => $bookID]);

	$linkDelete  = URL::createLink($moduleName, $controllerName, 'deleteBookOrder', ['id' => $id, 'book_id' => $bookID]);
	$linkEdit    = URL::createLink($moduleName, $controllerName, 'form', ['id' => $id, 'book_id' => $bookID]);
	$price = $arrPrice[$key];
	// $quantity = $arrQuantity[$key];
	$quantity = Helper::input("number", "form[qunatity]", "quantity-" . $bookID, $arrQuantity[$key], "book-name", 'data-id = "' . $bookID . '"');
	// $BookName = $arrBookName[$key];
	$BookName = HTML::select1("form[book-name]", "book-name", 'id = "select-' . $bookID . '"', $arrBookInShop ?? [], $bookID, $bookID, $id);
	$picture = Helper::showPicture('book', $arrBookPicture[$key], 'picture-' . $bookID);;


	$xhtml .= '	<tr>
					<td class="text-center">
						<div class="custom-control custom-checkbox">
							<input class="custom-control-input" type="checkbox" id="checkbox-' . $bookID . '" name="checkbox[]" value="' . $bookID . '">
							<label for="checkbox-' . $bookID . '" class="custom-control-label"></label>
						</div>
					</td>
					<td class="text-center">' . $id . '</td>
					<td class="text-center position-relative" id="book_id_' . $bookID . '">' . $bookID . '</td>
					<td class="text-center position-relative" id="price_' . $bookID . '">' . $price . '</td>
					<td class="text-center position-relative">' . $quantity . '</td>
					<td class="text-center position-relative">' . $BookName . '</td>
					<td class="text-center position-relative">' . $picture . '</td>
					
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

// $lblID       = Helper::cmsLinkSort('ID', 'id', $columnPost, $orderPost);
// $lblUserName    = Helper::cmsLinkSort('UserName', 'username', $columnPost, $orderPost);
// $lblStatus   = Helper::cmsLinkSort('Status', 'status', $columnPost, $orderPost);
// $lblDate = Helper::cmsLinkSort('Date', 'date', $columnPost, $orderPost);

$lblID = 'ID';
$lblBookID  = 'BookID';
$lblPrice  = 'Price';
$lblQuantity = 'Quantity';
$lblBookNames = 'BookName';
$lblBookPicture = 'Picture';
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
						<th class="text-center"><?php echo $lblBookID ?></th>
						<th class="text-center"><?php echo $lblPrice ?></th>
						<th class="text-center"><?php echo $lblQuantity ?></th>
						<th class="text-center"><?php echo $lblBookNames ?></th>
						<th class="text-center"><?php echo $lblBookPicture ?></th>
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



</div>