<?php	
	require_once PATH_LIBRARY_EXT . 'XML.php';

	$xhtml = '';

	if(!empty($this->Items)){
		foreach($this->Items as $key => $value){
			$name	 		= $value->name;
			$nameURL		= URL::filterURL($name);
			$id				= $value->id;
			$link	 		= URL::createLink('default', 'book', 'list', array('category_id' => $id), "$nameURL-$id.html");
			$picture 		= Helper::createImage('category', '60x90-', $value->picture, array('class' => 'thumb'));
			$xhtml 	.= '<div class="new_prod_box">
							<a href="'.$link.'">'.$name.'</a>
							<div class="new_prod_bg">
								<a href="'.$link.'">'.$picture.'</a>
							</div>           
						</div>';
		}
	}
?>

<!-- TITLE -->
<?php echo Helper::createTitle("$imageURL/bullet1.gif", 'Category book');?>

<!-- LIST CATEGORIES -->
<div class="new_products">
	<?php echo $xhtml;?>
</div>