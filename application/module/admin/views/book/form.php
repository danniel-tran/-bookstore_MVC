<?php 
	include_once (PATH_MODULE . 'admin/views/toolbar.php');
	include_once 'submenu/index.php';
	
	// Input
	$dataForm 			= $this->arrParam['form'];
	$inputName			= Helper::cmsInput('text', 'form[name]', 'name', $dataForm['name'], 'inputbox required', 40);
	$inputDescription	= '<textarea name="form[description]">'.$dataForm['description'].'</textarea>';
	$inputPrice			= Helper::cmsInput('text', 'form[price]', 'price', $dataForm['price'], 'inputbox required', 40);
	$inputSaleOff		= Helper::cmsInput('text', 'form[sale_off]', 'sale_off', $dataForm['sale_off'], 'inputbox', 40);
	$inputOrdering		= Helper::cmsInput('text', 'form[ordering]', 'ordering', $dataForm['ordering'], 'inputbox', 40);
	$inputToken			= Helper::cmsInput('hidden', 'form[token]', 'token', time());
	$slbStatus			= Helper::cmsSelectbox('form[status]', null, array('default' => '- Select status -', 1 => 'Publish', 0 => 'Unpublish'), $dataForm['status'], 'width: 180px');
	$slbSpecial			= Helper::cmsSelectbox('form[special]', null, array('default' => '- Select special -', 1 => 'Yes', 0 => 'No'), $dataForm['special'], 'width: 180px');
	$slbCategory		= Helper::cmsSelectbox('form[category_id]', 'inputbox', $this->slbCategory, $dataForm['category_id'], 'width: 180px');
	$inputPicture		= Helper::cmsInput('file', 'picture', 'picture', $dataForm['picture'], 'inputbox', 40);
	
	$inputID		= '';
	$rowID			= '';
	$picture		= '';
	if(isset($this->arrParam['id']) || $dataForm['id']){
		$inputID		= Helper::cmsInput('text', 'form[id]', 'id', $dataForm['id'], 'inputbox readonly');
		$inputUserName	= Helper::cmsInput('text', 'form[username]', 'username', $dataForm['username'], 'inputbox readonly', 40);
		$rowID			= Helper::cmsRowForm('ID', $inputID);
		$picture		= '<img src="'.URL_UPLOAD . 'book' . DS . '98x150-' . $dataForm['picture'].'">';
		$inputPictureHidden	= Helper::cmsInput('hidden', 'form[picture_hidden]', 'picture_hidden', $dataForm['picture'], 'inputbox', 40);
	}
	
	// Row
	$rowName		= Helper::cmsRowForm('Name', $inputName, true);
	$rowPicture		= Helper::cmsRowForm('Picture', $inputPicture . $picture . $inputPictureHidden);
	$rowDescription	= Helper::cmsRowForm('Description', $inputDescription);
	$rowPrice		= Helper::cmsRowForm('Price', $inputPrice, true);
	$rowSaleOff		= Helper::cmsRowForm('Sale Off', $inputSaleOff, true);
	$rowOrdering	= Helper::cmsRowForm('Ordering', $inputOrdering, true);
	$rowStatus		= Helper::cmsRowForm('Status', $slbStatus);
	$rowSpecial		= Helper::cmsRowForm('Special', $slbSpecial);
	$rowCategory	= Helper::cmsRowForm('Category ', $slbCategory);
	
	// MESSAGE
	$message	= Session::get('message');
	Session::delete('message');
	$strMessage = Helper::cmsMessage($message);
?>
<div id="system-message-container"><?php echo $strMessage . $this->errors;?></div>
<div id="element-box">
	<div class="m">
		<form action="#" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
			<!-- FORM LEFT -->
			<div class="width-100 fltlft">
				<fieldset class="adminform">
					<legend>Details</legend>
					<ul class="adminformlist">
						<?php echo $rowName . $rowPicture . $rowPrice . $rowSaleOff . $rowStatus . $rowSpecial .$rowCategory . $rowOrdering . $rowDescription . $rowID;?>
					</ul>
					<div class="clr"></div>
					<div>
						<?php echo $inputToken;?>
					</div>
				</fieldset>
			</div>
			<div class="clr"></div>
			<div>
			</div>
		</form>
		<div class="clr"></div>
	</div>
</div>
        
     