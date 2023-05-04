<?php
class Helper
{

	public static function slider($image, $url = "")
	{
		$xhtml = ' <div>
		<a href="' . $url . '" target="blank" class="home text-center">
			<img style="width:1920px, height:718px" src="' . URL_UPLOAD . "slider/" . $image . '" alt="" class="bg-img blur-up lazyload" alt="TITLE">
		</a>
	</div>';
		return $xhtml;
	}

	//===== COL ======
	public static function col($for, $name, $input)
	{
		$xhtml = sprintf('<div class="col-md-6">
							<label for="' . $for . '" class="required">' . $name . '</label>
							' . $input . '
						</div>');
		return $xhtml;
	}

	// Create Image
	public static function createImage($folder, $prefix, $pictureName, $attribute = null)
	{
		$class	= !empty($attribute['class']) ? $attribute['class'] : '';
		$width	= !empty($attribute['width']) ? $attribute['width'] : '';
		$height	= !empty($attribute['height']) ? $attribute['height'] : '';
		$strAttribute	= "class='$class' width='$width' height='$height'";

		$picturePath	= PATH_UPLOAD . $folder . DS . $prefix . $pictureName;
		if (file_exists($picturePath) == true) {
			$picture		= '<img  ' . $strAttribute . ' src="' . URL_UPLOAD . $folder . DS . $prefix . $pictureName . '">';
		} else {
			$picture	= '<img ' . $strAttribute . ' src="' . URL_UPLOAD . $folder . DS . $prefix . 'default.jpg' . '">';
		}

		return $picture;
	}


	// Create Input File
	public static function cmsInputFile($name, $image)
	{
		$xhtml = '  <div class="input-group">
						<div class="custom-file">
							<input name= "' . $name . '"  type="file" onchange="readURL(this)" class="custom-file-input" id="exampleInputFile">
							<label class="custom-file-label" for="exampleInputFile">Choose file</label>
						</div>
				    </div>' . $image;
		return $xhtml;
	}
	// Create Image
	public static function cmsImage($link = null)
	{
		$image = '<img  id="blah" class="hiderImg " src="' . $link . '" alt="your image" />';
		return $image;
	}

	// Create input 
	public static function cmsInput($type, $name, $id, $value, $class = null, $size = null)
	{
		$strSize	=	($size == null) ? '' : "size='$size'";
		$strClass	=	($class == null) ? '' : "class='$class'";

		$xhtml = "<input type='$type' name='$name' id='$id' value='$value' $strClass $strSize>";

		return $xhtml;
	}


	//===== RANDOM STRING ======
	public static function randomString($length = 8)
	{
		$arrCharacter = array_merge(range('a', 'z'), range(0, 9), range('A', 'Z'));
		$arrCharacter = implode('', $arrCharacter);
		$arrCharacter = str_shuffle($arrCharacter);

		$result       = substr($arrCharacter, 0, $length);
		return $result;
	}

	//===== SHOW ITEM STATUS ======
	public static function showItemStatus($value, $link, $id)
	{
		$class = 'danger';
		$icon = 'minus';
		if ($value == 1) {
			$class = 'success';
			$icon = 'check';
		}
		$xhtml = '<a href="javascript:changeStatus(\'' . $link . '\')" class="status-' . $id . ' my-btn-state rounded-circle btn btn-sm btn-' . $class . '"><i class="fas fa-' . $icon . '"></i></a>';
		return $xhtml;
	}
	//===== SHOW ITEM STATUS ======
	public static function showItemSpecial($value, $link, $id)
	{
		$class = 'danger';
		$icon = 'minus';
		if ($value == 1) {
			$class = 'success';
			$icon = 'check';
		}
		$xhtml = '<a href="javascript:changeSpecial(\'' . $link . '\')" class="special-' . $id . ' my-btn-state rounded-circle btn btn-sm btn-' . $class . '"><i class="fas fa-' . $icon . '"></i></a>';
		return $xhtml;
	}

	//===== SHOW ITEM GROUP ACP ======
	public static function showItemGroupACP($value, $link, $id)
	{
		$class = 'danger';
		$icon = 'minus';
		if ($value == 1) {
			$class = 'success';
			$icon = 'check';
		}
		$xhtml = '<a href="javascript:changeGroupACP(\'' . $link . '\')" class="groupACP-' . $id . ' my-btn-state rounded-circle btn btn-sm btn-' . $class . '"><i class="fas fa-' . $icon . '"></i></a>';
		return $xhtml;
	}

	//===== SHOW FILTER BUTTON ======
	public static function showFilterButton($module, $controller, $arr, $currentFilterStatus)
	{
		$xhtml = '';
		foreach ($arr as $key => $value) {
			$link = URL::createLink($module, $controller, 'index', ['status' => $key]);
			$name = '';
			switch ($key) {
				case 'all':
					$name = 'All';
					break;
				case 'active':
					$name = 'Active';
					break;
				case 'inactive':
					$name = 'Inactive';
					break;
			}
			$active = $key == $currentFilterStatus ? 'info' : 'secondary';
			$xhtml .= ' <a href="' . $link . '" class="mr-1 btn btn-sm btn-' . $active . '">' . $name . ' <span class="badge badge-pill badge-light">' . $value . '</span></a>';
		}
		return $xhtml;
	}

	//===== SELECT ======
	public static function select($name, $class, $arrValue, $keySelect = 'default', $style = null, $attribute = '')
	{
		$xhtml = '<select style="' . $style . '" name="' . $name . '" class="' . $class . '" ' . $attribute . '>';
		foreach ($arrValue as $key => $value) {

			if ($key == $keySelect && is_numeric($keySelect)) {
				$xhtml .= '<option selected value = "' . $key . '">' . $value . '</option>';
			} else {
				$xhtml .= '<option value = "' . $key . '">' . $value . '</option>';
			}
		}
		$xhtml .= '</select>';
		return $xhtml;
	}
	//===== SELECT ======
	public static function select1($name, $class, $id = null, $arrValue, $keySelect = 'default', $style = null, $attribute = '')
	{
		$xhtml = '<select id ="' . $id . '" style="' . $style . '" name="' . $name . '" class="' . $class . '" ' . $attribute . '>';
		foreach ($arrValue as $key => $value) {
			if ($key == $keySelect) {
				$xhtml .= '<option selected value = "' . $key . '">' . $value . '</option>';
			} else {
				$xhtml .= '<option value = "' . $key . '">' . $value . '</option>';
			}
		}
		$xhtml .= '</select>';
		return $xhtml;
	}


	// HIGHT LIGHT
	public static function highLight($input, $searchValue)
	{
		$result = $input;
		if ($searchValue != '') {
			$result = preg_replace("/" . preg_quote($searchValue, "/") . "/i", "<mark>$0</mark>", $input);
		}
		return $result;
	}

	// FORMATE DATE
	public static function formatDate($format, $value)
	{
		$result = '';
		if (!empty($value) && $value != '0000-00-00') {
			$result = date($format, strtotime($value));
		};
		return $result;
	}


	// CREATE BUTTON ADD THUMNAIL
	public static function buttonAddThumnail()
	{
		$button = '
		<div class = "div-new-thumbnail">
			<span class="btn btn-sm" id = "new-thumnail">+ Add New Thumbnail</span>
		</div>
		';
		return $button;
	}

	// CREATE INPUT+ROW THUMNAIL
	public static function inputThumbnail($name, $class = null, $id = null, $min = 'min= "0"')
	{
		$input = '<input type="file" onchange="readThumbImg(this)" name = "' . $name . '" id = "' . $id . '"  class = "' . $class . '" ' . $min . ' >';
		$input .= '<img class="thumbnail-alt" src="" alt="ALT IMAGE">';
		return $input;
	}
	public static function rowInputThumbnail($input)
	{
		$row = '<div class= "input-thumbnail">' . $input . '</div>';
		return $row;
	}

	// CREATE INPUT+ROW THUMNAIL ORDERING
	public static function inputThumbnailOrdering($name, $value = null, $class = null, $min = 'min="1"', $id = null)
	{
		$input = '<input type="number" name = "' . $name . '" value= "' . $value . '" id = "' . $id . '"  class = "' . $class . '" placeholder = "Ordering-Thumbnail" ' . $min . '>';
		return $input;
	}

	public static function rowInputOrderingThumbnail($input, $img)
	{
		$srcImg = empty($img) ? null : URL_UPLOAD . 'book/picture-detail/' . $img;
		$pic  = '
		<img class = "show-thumbnail" src = "' . $srcImg . '" alt = "show-thumbnail">';
		$xhtml = '<div class = "container-ordering-thumbnail">
		' . $input  . '
		</div>' . $pic;
		return $xhtml;
	}

	//===== CREATE ROW THUMBNAIL   ======
	public static function rowThumnail($labelName, $input, $require = false)
	{
		if ($require == true) $require = 'required';
		$xhtml = ' <div class="form-group row align-items-center main-thumbnail">
						<label for="' . $labelName . '" class="col-sm-2 col-form-label text-sm-right ' . $require . '">' . $labelName . '</label>
						<div class="col-xs-12 col-sm-8 flex-thumb">
						' . $input . '
						</div>
					</div>';
		return $xhtml;
	}


	// HTML THUMB
	public static function HtmlThumb($row)
	{
		$xhtml = '<div>' . $row . "</div>";
		return $xhtml;
	}
	//===== CREATE ROW   ======
	public static function row($labelName, $input, $require = false)
	{

		if ($require == true) $require = 'required';
		$xhtml = ' <div class="form-group row align-items-center">
						<label for="' . $labelName . '" class="col-sm-2 col-form-label text-sm-right ' . $require . '">' . $labelName . '</label>
						<div class="col-xs-12 col-sm-8">
						' . $input . '
						</div>
					</div>';
		return $xhtml;
	}

	public static function rowB($labelName, $input, $require = false)
	{

		if ($require == true) $require = 'required';
		$xhtml = ' <div class="form-group row align-items-center">
						<label class="col-sm-2 col-form-label text-sm-right ' . $require . '">' . $labelName . '</label>
						<div class="col-xs-12 col-sm-8">
						' . $input . '
						</div>
					</div>';
		return $xhtml;
	}

	//===== CREATE TITLE SORT   ======
	public static function cmsLinkSort($name, $column, $columnPost, $orderPost)
	{
		$img = '';
		$order = ($orderPost == 'desc') ? 'asc' : 'desc';
		if ($column == $columnPost) {
			$img = ' <img src="' . URL_TEMPLATE . 'admin/theme_admin/images/sort_' . $orderPost . '.png" alt=""/>';
		}
		$xhtml = '<a href="#" onclick="javascript:sortList(\'' . $column . '\',\'' . $order . '\')">' . $name . $img . '</a>';
		return $xhtml;
	}

	//===== CREATE LINK NAME   ======
	public static function cmsLinkTittle($link, $name)
	{
		$xhtml = '<a href="' . $link . '">' . $name . '</a>';
		return $xhtml;
	}

	// BUTTON IN ADD GROUP
	public static function button($link, $class, $name, $js = null)
	{
		if ($js == null) {

			$xhtml = ' <a href="javascript:submitForm(\'' . $link . '\')" class="' . $class . '"> ' . $name . '</a>';
		} else {
			$xhtml = ' <a href="' . $link . '" class="' . $class . '"> ' . $name . '</a>';
		}
		return $xhtml;
	}
	//===== BUTTON ======
	public static function button1($type, $id, $name, $value, $value1)
	{
		$xhtml = sprintf('<button type="%s" id="%s" name="%s" value="%s" class="btn btn-solid">%s</button>', $type, $id, $name, $value, $value1);

		return $xhtml;
	}

	//===== INPUT ======
	public static function input($type, $name, $id, $value, $class = null, $attribute = null, $min = 'min="0.0"')
	{
		$strClass = ($class == null) ? '' : 'class="' . $class . '"';
		$xhtml = '<input type="' . $type . '" name="' . $name . '" id="' . $id . '" value="' . $value . '"' . $strClass . $attribute . $min . '>';
		return $xhtml;
	}
	// INPUT
	public static function input1($name)
	{

		// $xhtml = '  <div class="input-group">
		// 				<div class="custom-file">
		// 					<input name= "' . $name . '"  type="file" onchange="readURL(this)" class="custom-file-input" id="exampleInputFile">
		// 					<label class="custom-file-label" for="exampleInputFile">Choose file</label>
		// 				</div>

		// 				<div class=	"input-group-append">
		// 					<span class="input-group-text" id="">Upload</span>
		// 				</div> 
		// 		    </div>

		// 			<img   id="blah" class="hiderImg" src="" alt="your image" />

		// 			';
		$xhtml = '  <div class="input-group">
						<div class="custom-file">
							<input name= "' . $name . '"  type="file" onchange="readURL(this)" class="custom-file-input" id="exampleInputFile">
							<label class="custom-file-label" for="exampleInputFile">Choose file</label>
						</div>
				    </div>
					
					<img   id="blah" class="hiderImg" src="" alt="your image" />
					
					';

		// $xhtml = '  <input type=\'file\' onchange="readURL(this);" />
		//   			  <img id="blah" class="hiderImg" src="" alt="your image" />';
		return $xhtml;
	}
	//===== TEXTAREA ======
	public static function textarea($rows, $cols, $name, $form, $value = null)
	{
		$xhtml = '<textarea rows="' . $rows . '" cols="' . $cols . '" name="' . $name . '" form="' . $form . '">' . $value . ' </textarea>';
		return $xhtml;
	}

	//===== SHOW ITEM ORDERING ======
	public static function showItemOrdering($id, $ordering)
	{
		$xhtml = Helper::input("number", "chkOrdering[$id]", "chkOrdering[$id]", $ordering, 'chkOrdering form-control form-control-sm m-auto text-center', 'style="width: 100%" data-id="' . $id . '" min="1"');
		return $xhtml;
	}

	// CREATE ITEM HISTORYc
	public static function showItemHistory($by, $time)
	{
		$time = Helper::formatDate(DATETIME_FORMAT, $time);
		$xhtml = '
		  <p class="mb-0 history-by"><i class="far fa-user"></i> ' . $by . '</p>
		  <p class="mb-0 history-time"><i class="far fa-clock"></i> ' . $time . '</p>
		  ';
		return $xhtml;
	}

	//===== CREATE NOTIFY ======
	public static function createNotify($type, $message)
	{
		return ['type' => $type, 'message' => $message];
	}

	//===== SHOW TOAST MESSAGE ======
	public static function showToastMessage()
	{
		$message = Session::get('notify') ?? '';
		Session::delete('notify');

		if (!empty($message)) {

			return "showToast(\"" . $message['type'] . "\", \"" . $message['message'] . "\")";
		}
	}

	//===== SHOW SMALL BOX DASHBOARD ======
	public static function showBoxDashboard($name, $itemsCount, $link, $bgClass, $icon)
	{
		$xhtml = sprintf('
        <div class="small-box %s">
            <div class="inner">
                <h3>%s</h3>
                <p>%s</p>
            </div>
            <div class="icon text-white">
                <i class="ion %s"></i>
            </div>
            <a href="%s" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
        ', $bgClass, $itemsCount, $name, $icon, $link);

		return $xhtml;
	}

	//===== CREATE SIDE BAR MENU ITEM ======
	public static function menuSidebar($controller, $action, $menuItem)
	{
		$xhtml = '';
		if (isset($menuItem['child'])) {
			if ($menuItem['data-active'] == $controller) {
				$activeParent = 'active';
				$menuOpen = 'menu-open';
			}
			$xhtml .= ' 
						  <li class="nav-item has-treeview ' . $menuOpen . '">
							<a href="' . $menuItem['link'] . '" class="nav-link ' . $activeParent . '" data-active="' . $menuItem['data-active'] . '">
								<i class="icon-color nav-icon fas fa-' . $menuItem['icon'] . '"></i>
								<p>' . $menuItem['name'] . '<i class="fas fa-angle-left right"></i></p>
							</a>
							<ul class="nav nav-treeview"> ';
			foreach ($menuItem['child'] as $menuItemChild) {
				$activeChild = ($activeParent && $menuItemChild['data-active'] == $action) ? 'active' : '';
				$xhtml .= '
						 <li class="nav-item">
							<a href="' . $menuItemChild['link'] . '" class="nav-link ' . $activeChild . '" data-active="' . $menuItemChild['data-active'] . '">
							<ul>
							 	<i class="nav-icon fas fa-' . $menuItemChild['icon'] . '"></i>
								<p>' . $menuItemChild['name'] . '</p>
							</ul>
							</a>
						</li>
						';
			}
			$xhtml .= '</ul></li>';
		} else {
			$activeParent = $menuItem['data-active'] == $controller ? 'active' : '';
			$xhtml .= sprintf('
			 <li class="nav-item">
				 <a href="%s" class="nav-link %s" data-active="%s">
					 <i class="nav-icon fas fa-%s"></i>
					 <p>%s</p>
				 </a>
			 </li>
			 ', $menuItem['link'], $activeParent, $menuItem['data-active'], $menuItem['icon'], $menuItem['name']);
		}
		return $xhtml;
	}
	//===== SHOW USER INFO ======
	public static function showUserInfo($arr)
	{
		$xhtml = '';
		foreach ($arr as $item) {
			$xhtml .= sprintf('<p class="mb-0"><b>%s</b>: %s</p>', $item['name'], $item['value']);
		}
		return $xhtml;
	}

	public static function myTruncate($description)
	{
		$numwords1 =  str_word_count($description, 0);
		$numwords = 14;
		preg_match("/(\S+\s*){0,$numwords}/", $description, $regs);
		$shortdesc = trim($regs[0]);
		if ($numwords1 > 14) {
			$shortdesc = $shortdesc . '...';
		}
		return $shortdesc;
	}

	//===== TAO MOI HANG SAN PHAM ======
	public static function createRowProduct($name, $saleOff, $id, $image, $price)
	{
		$link      = URL::createLink('frontend', 'book', 'detail', ['book_id' => $id]);
		$newPrice  = ($price - ($price * $saleOff / 100));
		$shortname = Helper::myTruncate($name); //$shortname = substr($name, 0, 50) . '...';
		$picture   = URL_UPLOAD . 'book' . DS . $image;
		$xhtml     = '
		 <div class="product-box">
			 <div class="img-wrapper">
				 <div class="lable-block">
					 <span class="lable4 badge badge-danger" style="height:50px"> -' . $saleOff . '%</span>
				 </div>
				 <div class="front">
					 <a href="' . $link . '">
						 <img src="' . $picture . '" class="img-fluid blur-up lazyload bg-img" alt="product">
					 </a>
				 </div>
				 <div class="cart-info cart-wrap">
					 <a href="#" title="Add to cart"><i class="ti-shopping-cart add-to-cart" data-id="' . $id . '"></i></a>
					 <a href="#"  title="Quick View"><i class="ti-search quick-view" data-id="' . $id . '" data-toggle="modal" data-target="#quick-view"></i></a>
				 </div>
			 </div>
			 <div class="product-detail">
				 <div class="rating">
					 <i class="fa fa-star"></i>
					 <i class="fa fa-star"></i>
					 <i class="fa fa-star"></i>
					 <i class="fa fa-star"></i>
					 <i class="fa fa-star"></i>
				 </div>
				 <a href="' . $link . '" title="' . $name . '">
					 <h6>' . $shortname . '</h6>
				 </a>
				 <h4 class="text-lowercase">' . number_format($newPrice) . ' đ <del>' . number_format($price) . ' đ</del></h4>
			 </div>
		 </div>
		 ';
		return $xhtml;
	}

	//===== CREATE CATEGORY ITEM ======
	public static function createCategoryItem($id, $name, $class)
	{
		$link = URL::createLink('frontend', 'book', 'list', ['category_id' => $id]);
		$xhtml = '
				  <div class="custom-control custom-checkbox collection-filter-checkbox pl-0 category-item">
					  <a class="' . $class . '" href="' . $link . '">' . $name . '</a>
				  </div>
				  ';

		return $xhtml;
	}

	//===== TIEU DE DANH MUC NOI BAT ======
	public static function tieuDeDanhMucNoiBat($class, $href, $data_category, $name)
	{
		$xhtml = sprintf('<li class="%s"><a href="tab-category-%s" class="my-product-tab" data-category="%s">%s</a></li>', $class, $href, $data_category, $name);
		return $xhtml;
	}


	//===== CREATE SPECIAL BOOK ======
	public static function createSpecialBook($arrValue)
	{
		$xhtml = '<div>';
		foreach ($arrValue as $key => $value) {
			$picture = URL_UPLOAD . 'book' . DS . $value['picture'];
			$link = URL::createLink('frontend', 'book', 'detail', ['book_id' => $value['id']]);
			$linkOrder = URL::createLink('frontend', 'book', 'order', ['book_id' => $value['id'], 'price' => $value['price']]);
			$newPrice = ($value['price'] - ($value['price'] * $value['sale_off'] / 100));
			$xhtml .= '
                <div class="media">
                    <a href="' . $link . '">
                        <img style="width:82px; height:120px"  class="img-fluid blur-up lazyload" src="' . $picture . '" alt="' . $value['name'] . '"></a>
                    <div class="media-body align-self-center">
                        <div class="rating">
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                            <i class="fa fa-star"></i>
                        </div>
                        <a href="' . $link . '" title="' . $value['name'] . '">
                            <h6>' . $value['name'] . '</h6>
                        </a>
                        <h4 class="text-lowercase">' . number_format($newPrice) . ' đ</h4>
                    </div>
                </div>
                ';
		}
		$xhtml .= '</div>';
		return $xhtml;
	}


	//===== SHOW CATEGORY  ======
	public static function showCategory($img1, $name, $link)
	{
		$img = URL_PUBLIC . 'files' . DS .  'category' . DS . $img1;
		$xhtml = ' <div class="product-box">
					   <div class="img-wrapper">
						   <div class="front">
							   <a href="' . $link . '"><img src="' . $img . '" class="img-fluid blur-up lazyload bg-img" alt=""></a>
						   </div>
					   </div>
					   <div class="product-detail">
						   <a href="' . $link . '"><h4>' . $name . '</h4></a>
					   </div>
				   </div>';
		return $xhtml;
	}

	// CREATE ITEM CHECKBOX
	public static function showItemCheckbox($id)
	{
		$xhtml = '
		<div class="custom-control custom-checkbox">
			<input class="custom-control-input" type="checkbox" id="checkbox-' . $id . '" name="checkbox[]" value="' . $id . '">
			<label for="checkbox-' . $id . '" class="custom-control-label"></label>
		</div>
		';
		return $xhtml;
	}
	//===== SHOW PICTURE ======
	public static function showPicture($name, $picture, $id = null)
	{
		$file = PATH_UPLOAD . $name . DS . '' . $picture;
		$idElement = '';
		if ($id !== null) {
			$idElement = 'id="' . $id . '"';
		}
		$defaultPictureSrc = "default.jpg";
		if (!file_exists($file)) {
			$xhtml = '
					<img style="width:60px; height:90px" ' . $idElement . ' src="' . URL_UPLOAD . "default" . DS . '' . $defaultPictureSrc . '">
				';
		} else {
			$xhtml = '
					<img style="width:60px; height:90px" ' . $idElement . ' src="' . URL_UPLOAD . $name . DS . '' . $picture . '">
				';
		}
		return $xhtml;
	}



	// SHOW SPECIAL
	public static function showSpecial($link, $state)
	{
		$classDOM = is_numeric($state) ? 'my-btn-state btn-special' : '';
		$classDOM1 = !(is_numeric($state)) ? ' btn-status' : '';
		$class = 'success';
		$icon = 'check';
		if ($state == 'inactive' || $state == '0') {
			$class = 'danger';
			$icon = 'minus';
		}
		// <a href="i" class="my-btn-state rounded-circle btn btn-sm btn-danger"><i class="fas fa-minus"></i></a>
		$xhtml = '
				  <a href="' . $link . '" class="' . $classDOM . $classDOM1 . ' rounded-circle  btn btn-sm btn-' . $class . '"><i class="fas fa-' . $icon . '"></i></a>
				  ';
		return $xhtml;
	}

	//===== SHOW ACTION BUTTON ======
	public static function showActionButton($moduleName, $controllerName, $id)
	{
		$templateButton = [
			'view'          => [
				'class' => 'btn-primary',
				'icon' => 'eye',
				'text' => 'View',
				'link' => URL::createLink($moduleName, $controllerName, 'detail', ['id' => $id])
			],

			'edit'          => [
				'class' => 'btn-info',
				'icon' => 'pencil-alt',
				'text' => 'Edit',
				'link' => URL::createLink($moduleName, $controllerName, 'form', ['id' => $id])
			],

			'delete'        => [
				'class' => 'btn-danger btn-delete-item',
				'icon' => 'trash-alt', 'text' => 'Delete',
				'link' => URL::createLink($moduleName, $controllerName, 'delete', ['id' => $id])
			],

			'reset-password' => [
				'class' => 'btn-secondary',
				'icon' => 'key',
				'text' => 'Reset Password',
				'link' => URL::createLink($moduleName, $controllerName, 'resetPassword', ['id' => $id])
			]
		];

		$buttonInArea = [
			'default'  => ['edit', 'delete'],
			'group'    => ['edit', 'delete'],
			'category' => ['edit', 'delete'],
			'user'     => ['reset-password', 'edit', 'delete'],
			'cart'     => ['view'],
		];

		$controllerName = (array_key_exists($controllerName, $buttonInArea)) ? $controllerName : 'default';
		$listButton     = $buttonInArea[$controllerName];

		$xhtml = '';

		foreach ($listButton as $btn) {
			$currentButton = $templateButton[$btn];
			$xhtml .= sprintf('
			  <a href="%s" class="rounded-circle btn btn-sm %s" title="%s" data-toggle="tooltip">
				  <i class="fas fa-%s"></i>
			  </a>
			  ', $currentButton['link'], $currentButton['class'], $currentButton['text'], $currentButton['icon']);
		}

		return $xhtml;
	}
	//======HTML FORM PICTURE ==================================
	public static function HtmlFormPics($id, $folderUpload = "slider", $controller = "slider", $folder = URL_UPLOAD, $fileUpload = null)
	{
		if (file_exists(PATH_UPLOAD . $folderUpload . DS . $fileUpload)) {
			$filePIC = URL_UPLOAD . $folderUpload . DS . $fileUpload;
		} else {
			$filePIC = URL_UPLOAD;
		};
		$xhtml = '
		<div>
			<div class="custom-file">
				<input name="update-img" type="file" onchange="readPictureUrlId(this,' . $id . ')" class="custom-file-input custom-input" id="input-picture-' . $id . '">
				<label class="custom-file-label slider-pic" for="input-picture-' . $id . '">Change file</label>
			</div>			
			<img id= "show-image-' . $id . '" class = "slider-pic-input" alt="img" src="' . $filePIC . '"></img>
			<div id="show-selection-' . $id . '" class="select-cancel">
				<span>
					<button>
						<a href="javascript:changePictureSlider(\'/bookstoreClass/index.php?module=backend&controller=' . $controller . '&action=ajaxChangePicture&id-pic=' . $id . '\',' . $id . ')">Change</a>
					</button>
				</span>
				<span>
					<button>
						<a href="javascript:changePictureSlider(\'/bookstoreClass/index.php?module=backend&controller=slider&action=ajaxChangePicture&id-pic=' . $id . '\',' . $id . ')">Cancel</a>
					</button>
				</span>
			</div>
		</div>';
		return $xhtml;
	}

	// Alternative Image
	public static function changeThumbnailImage()
	{
		$xhtml = "";

		return $xhtml;
	}

	// input datetime-local
	public static function dateTimeInput($name, $value = null, $id = null)
	{
		$xhtml = '
		<input type="datetime-local" id="' . $id . '" name="' . $name . '" value = "' . $value . '">
		';
		return $xhtml;
	}
}
