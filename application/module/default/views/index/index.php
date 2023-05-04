<!-- SPECIAL BOOKS -->
<?php echo Helper::createTitle("$imageURL/bullet1.gif", 'Featured books');?>

<?php 
	$xhtml = '';
	if(!empty($this->specialBooks)){
		foreach($this->specialBooks as $key => $value){
			$name	= $value['name'];
			
			$bookID			= $value['id'];
			$catID			= $value['category_id'];
			$bookNameURL	= URL::filterURL($name);
			$catNameURL		= URL::filterURL($value['category_name']);
			
			$link	= URL::createLink('default', 'book', 'detail', array('category_id' => $value['category_id'],'book_id' => $value['id']), "$catNameURL/$bookNameURL-$catID-$bookID.html");
			
			$description	= substr($value['description'], 0, 200);
			$picture 		= Helper::createImage('book', '98x150-', $value['picture']);
			
			$xhtml 	.= '<div class="feat_prod_box">
							<div class="prod_img"><a href="'.$link.'">'.$picture.'</a></div>
					
							<div class="prod_det_box">
								<div class="box_top"></div>
								<div class="box_center">
									<div class="prod_title">'.$name.'</div>
									<p class="details">'.$description.'</p>
									<a href="'.$link.'" class="more">- more details -</a>
									<div class="clear"></div>
								</div>
								<div class="box_bottom"></div>
							</div>
							<div class="clear"></div>
						</div>';
		}
	}
	echo $xhtml;
?>

<!-- NEW BOOKS -->
<?php echo Helper::createTitle("$imageURL/bullet2.gif", 'New books');?>

<div class="new_products">
<?php 
	$xhtmlNewBooks = '';
	if(!empty($this->newBooks)){
		foreach($this->newBooks as $key => $value){
			$name	= $value['name'];
	
			$bookID			= $value['id'];
			$catID			= $value['category_id'];
			$bookNameURL	= URL::filterURL($name);
			$catNameURL		= URL::filterURL($value['category_name']);
				
			$link	= URL::createLink('default', 'book', 'detail', array('category_id' => $value['category_id'],'book_id' => $value['id']), "$catNameURL/$bookNameURL-$catID-$bookID.html");

			$description	= substr($value['description'], 0, 200);
			$picture 		= Helper::createImage('book', '98x150-', $value['picture'], array('class' => 'thumb', 'width' => 60, 'height' => 90));
			
			$xhtmlNewBooks 	.= '<div class="new_prod_box">
									<a href="'.$link.'">'.$name.'</a>
									<div class="new_prod_bg">
										<a href="'.$link.'">'.$picture.'</a>
									</div>
								</div>';
		}
	}
	echo $xhtmlNewBooks;
?>
</div>






