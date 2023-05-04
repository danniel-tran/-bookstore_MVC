<?php
	$dataForm		= $this->arrParam['form'];
	
	// Input
	$inputSubmit	= Helper::cmsInput('submit', 'form[submit]', 'submit', 'register', 'register');
	$inputToken		= Helper::cmsInput('hidden', 'form[token]', 'token', time()); 
	
	// Row
	$rowUserName	= Helper::cmsRow('Username', Helper::cmsInput('text', 'form[username]', 'username', $dataForm['username'], 'contact_input'));
	$rowFullName	= Helper::cmsRow('Full Name', Helper::cmsInput('text', 'form[fullname]', 'fullname', $dataForm['fullname'], 'contact_input'));
	$rowPassword	= Helper::cmsRow('Password', Helper::cmsInput('text', 'form[password]', 'password', $dataForm['password'], 'contact_input'));
	$rowEmail		= Helper::cmsRow('Email', Helper::cmsInput('text', 'form[email]', 'email', $dataForm['email'], 'contact_input'));
	$rowSubmit		= Helper::cmsRow('Submit', $inputToken . $inputSubmit, true);
	
	$linkAction		= URL::createLink('default', 'index', 'register');
?>

<!-- TITLE -->
<?php echo Helper::createTitle("$imageURL/bullet1.gif", 'Đăng ký thành viên');?>

<div class="feat_prod_box_details">
	<div class="contact_form">
		<div class="form_subtitle">create new account</div>
		<?php echo $this->errors;?>
		<form name="adminform" action="<?php echo $linkAction?>" method="POST">
			<?php echo $rowUserName . $rowFullName . $rowPassword . $rowEmail . $rowSubmit;?>
		</form>
	</div>
</div>
<div class="clear"></div>