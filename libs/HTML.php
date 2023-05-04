<?php
class HTML
{
    public static function selectMulti($name, $class, $arrValue, $keySelect = 'default', $style = null, $attribute = '', $multiple = "multiple")
    {
        $xhtml = '<select style="' . $style . '" name="' . $name . '" class="' . $class . '" ' . $attribute . $multiple . '>';
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
    public static function tabTitle($href, $id, $name, $class = null)
    {
        $xhtml = '
                <li class="' . $class . '">
                    <a href="' . $href . '" class="my-product-tab" data-category="' . $id . '">' . $name . '</a>
                </li>                  
            ';
        return $xhtml;
    }

    //===== SELECT ======
    public static function select1($name, $class, $id = null, $arrValue, $keySelect = 'default', $idBookDefault, $idCart, $style = null, $attribute = '')
    {
        $xhtml = '<select data-id-cart = "' . $idCart . '" data-id="' . $idBookDefault . '"' .  $id . ' style="' . $style . '" name="' . $name . '" class="' . $class . '" ' . $attribute . '>';
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


    // CREATE BUTTON ADD THUMNAIL
    public static function buttonBookCart($class, $name, $id = null)
    {
        $button = '
			<div class = "' . $class . '">
				<span class="btn btn-sm" id = "' . $id . '">' . $name . '</span>
			</div>
		';
        return $button;
    }

    // Book In Cart Html
    public static function createBookInCartHTML($name, $selectbox, $quantity = null, $id = null)
    {
        $inputBookID = ' <input type="hidden" name="form[quantity][]" value="" class="form-control form-control-sm">';
        $xhtml = '
                <div class="form-group row align-items-center book-info">
                    <label class="col-sm-2 col-form-label text-sm-right">' . $name . '</label>
                    <div class="col-xs-12 col-sm-8 book-input">
                        ' . $selectbox . '
                        <input type="number" name="form[book-quantity][]"  value="' . $quantity . '" class="form-control form-control-sm" min="0" placeholder="Quantity">
                        <a class="btn delete-book"><i class="far fa-trash-alt"></i></a>
                    </div>
                </div>';
        return $xhtml;
    }
}
