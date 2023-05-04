<?php
	require_once PATH_LIBRARY_EXT . 'XML.php';
	
	$data	= XML::getContentXML('categories.xml');

	if(isset($this->arrParam['category_id'])){
		$cateID	= $this->arrParam['category_id'];
	}
	
	$xhtml		= '';
	if(!empty($data)){
		foreach($data as $key => $value){
			$name	 		= $value->name;
			$id				= $value->id;
			$nameURL		= URL::filterURL($name);
			$link	 		= URL::createLink('default', 'book', 'list', array('category_id' => $id), "$nameURL-$id.html");
			
			if($cateID == $value->id){
				$xhtml	.= '<li><a class="active" title="'.$name.'" href="'.$link.'">'.$name.'</a></li>';
			}else{
				$xhtml	.= '<li><a title="'.$name.'" href="'.$link.'">'.$name.'</a></li>';
			}
		}
	}
?>
<div class="right_box">
	<!-- TITLE -->
	<?php echo Helper::createTitle("$imageURL/bullet5.gif", 'Categories');?>
	
	<ul class="list"><?php echo $xhtml;?></ul>
</div>
<div class="clear"></div>