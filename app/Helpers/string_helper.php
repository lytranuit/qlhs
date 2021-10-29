<?php

if (!function_exists('str_slug')) {
    /**
     * Convert string to slug.
     *
     * @param string $string
     * @return mixed
     */
    function str_slug(string $str)
    {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return $str;
    }
}

if (!function_exists('str_snake')) {
    /**
     * Convert string to friendly file name.
     *
     * @param string $string
     * @return mixed
     */
    function str_snake(string $string)
    {
        return str_replace(' ', '_', strtolower($string));
    }
}

if (!function_exists('class_basename')) {
    /**
     * Get the class "basename" of the given object / class.
     *
     * @param  string|object $class
     * @return string
     */
    function class_basename($class)
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }
}

if (!function_exists('str_starts_with')) {
    /**
     * Determine if a given string starts with a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function str_starts_with($haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && substr($haystack, 0, strlen($needle)) === (string) $needle) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('str_studly')) {
    /**
     * Convert a value to studly caps case.
     *
     * @param  string  $value
     * @return string
     */
    function str_studly($value)
    {
        $value = ucwords(str_replace(['-', '_'], ' ', $value));
        return str_replace(' ', '', $value);
    }
}

if (!function_exists('str_contains')) {
    /**
     * Determine if a given string contains a given substring.
     *
     * @param  string  $haystack
     * @param  string|array  $needles
     * @return bool
     */
    function str_contains(string $haystack, $needles)
    {
        foreach ((array) $needles as $needle) {
            if ($needle !== '' && mb_strpos($haystack, $needle) !== false) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('str_camel')) {
    /**
     * Convert a value to camel case.
     *
     * @param  string  $value
     * @return string
     */
    function str_camel($value)
    {
        return lcfirst(str_studly($value));
    }
}

if (!function_exists('str_plural')) {
    /**
     * Get the plural form of an English word.
     *
     * @param  string  $value
     * @return string
     */
    function str_plural($value)
    {
        return plural($value);
    }
}

if (!function_exists('str_plural_studly')) {
    /**
     * Pluralize the last word of an English, studly caps case string.
     *
     * @param  string  $value
     * @return string
     */
    function str_plural_studly($value)
    {
        $parts = preg_split('/(.)(?=[A-Z])/u', $value, -1, PREG_SPLIT_DELIM_CAPTURE);

        $lastWord = array_pop($parts);

        return implode('', $parts) . str_plural($lastWord);
    }
}



if (!function_exists('area_current')) {

    function area_current()
    {

        helper("cookie");

        // store a cookie value

        $area_current = get_cookie("area_current");

        return $area_current;
    }
}

if (!function_exists('html_nestable')) {

    function html_nestable($array, $column, $parent, $controller = '')
    {
        $html = "";
        $return = array_filter((array) $array, function ($item) use ($column, $parent) {
            return $item[$column] == $parent;
        });
        ///Bebin Tag
        if ($parent == 0) {
            $id_nestable = "id='nestable'";
        } else {
            $id_nestable = "";
        }
        $html .= '<ol class="dd-list" ' . $id_nestable . '>';
        ///Content
        foreach ($return as $row) {
            $sub_html = "";
            $is_deleted = true;

            $child =  array_filter((array) $array, function ($item) use ($column, $row) {
                return $item[$column] == $row['id'];
            });
            $delete_html = "";
            if ($is_deleted && in_groups(array("admin", "editor"))) {
                $delete_html = '<button class="btn btn-sm btn-outline-light dd-item-delete">
                <i class="far fa-trash-alt"></i>
            </button>';
            }
            $html .= '<li class="dd-item" id="menuItem_' . $row['id'] . '" data-id="' . $row['id'] . '">
                            <div class="dd-handle">
                             ' . $sub_html . '
                                <div>' . (count($child) > 0 ? '<span class="showhide">-</span>' : "") . '<a href="' . base_url("admin/$controller/edit/" . $row['id']) . '">' . $row['name_vi'] . '</a></div>
                                <div class="dd-nodrag btn-group ml-auto">
                                    ' . $delete_html . '
                                </div>
                            </div>';
            $html .= html_nestable((array) $array, $column, $row['id'], $controller);
            $html .= '</li>';
        }
        ///End Tag
        $html .= '</ol>';

        return $html;
    }
}
if (!function_exists('html_product_category_nestable')) {

    function html_product_category_nestable($array, $column, $parent)
    {
        $html = "";
        $return = array_filter((array) $array, function ($item) use ($column, $parent) {
            return $item[$column] == $parent;
        });
        ///Bebin Tag
        if ($parent == 0) {
            $html .= '<ul style="list-style: none;padding:0" class="vitri">';
        } else {
            $html .= '<ul style="list-style: none;padding-left: 20px;">';
        }
        ///Content
        foreach ($return as $row) {
            $sub_html = "";

            $html .= '<li><div class="custom-checkbox custom-control">
            <input name="category_list[]" type="checkbox" id="eCheckbox' . $row['id'] . '" class="custom-control-input" value="' . $row['id'] . '">
            <label class="custom-control-label" for="eCheckbox' . $row['id'] . '">' . $row['name_vi'] . '</label>
        </div>';
            $html .= html_product_category_nestable((array) $array, $column, $row['id']);
            $html .= '</li>';
        }
        ///End Tag
        $html .= '</ul>';

        return $html;
    }
}


if (!function_exists('current_language')) {

    function current_language()
    {
        $language = \Config\Services::language();
        $short_lang =  $language->getLocale();
        return $short_lang;
    }
}

if (!function_exists('pick_language')) {

    function pick_language($data, $struct = 'name_')
    {
        $language = \Config\Services::language();
        $short_lang =  $language->getLocale();
        $data = (array) $data;
        if (isset($data[$struct . $short_lang]) && $data[$struct . $short_lang] != "") {
            return $struct . $short_lang;
        } else {
            return $struct . 'vi';
        }
    }
}

if (!function_exists('language_current')) {

    function language_current()
    {
        $language = \Config\Services::language();
        return $language->getLocale();
    }
}

if (!function_exists('split_string')) {

    function split_string($str, $length)
    {
        $str = strip_tags($str);
        if (strlen($str) > $length) {
            $str = mb_substr($str, 0, $length) . "...";
        }
        return $str;
    }
}






if (!function_exists('url_product_list')) {

    function url_product_list($id)
    {
        $url = base_url("danh-muc");
        if ($id > 0) {
            $category_model = model("CategoryModel");
            $category = $category_model->find($id);
            $url = base_url("danh-muc/" . ($category->slug != '' ? $category->slug : str_slug($category->name_vi)) . "-c$id.html");
        }
        return $url;
    }
}

if (!function_exists('url_news_list')) {

    function url_news_list($id)
    {
        $url = base_url("bang-tin");
        if ($id > 0) {
            $url = base_url("bang-tin?tag_id=$id");
        }
        return $url;
    }
}


if (!function_exists('url_page_list')) {

    function url_page_list($id)
    {
        $url = base_url();
        if ($id > 0) {
            $url = base_url("page/view/$id");
        }
        return $url;
    }
}


if (!function_exists('url_product')) {

    function url_product($product)
    {
        $url = base_url();
        if ($product) {
            $url = base_url("san-pham/c$product->id.html");
        }
        return $url;
    }
}

if (!function_exists('url_product_byid')) {

    function url_product_byid($id)
    {
        $url = base_url();
        if ($id) {
            $model = model("TagModel");
            $object = $model->find($id);
            $url = base_url("san-pham/c$object->id.html");
        }
        return $url;
    }
}
if (!function_exists('url_page')) {

    function url_page($id)
    {
        $url = base_url();
        if ($id > 0) {

            $page_model = model("PageModel");
            $page = $page_model->find($id);
            $url = base_url("page/" . ($page->slug != '' ? $page->slug : str_slug($page->title_vi)) . "-c$id.html");
        }
        return $url;
    }
}

if (!function_exists('url_tag')) {

    function url_tag($tag)
    {
        $url = base_url();
        if ($tag) {
            $url = base_url("tin-tuc/c$tag->id.html");
        }
        return $url;
    }
}
if (!function_exists('url_tag_byid')) {

    function url_tag_byid($id = NULL)
    {
        $url = base_url();
        if ($id) {
            $model = model("TagModel");
            $object = $model->find($id);
            $url = base_url("tin-tuc/c$object->id.html");
        }
        return $url;
    }
}
if (!function_exists('url_news')) {

    function url_news($news)
    {
        $url = base_url();
        if ($news) {
            $url = base_url("post/c$news->id.html");
        }
        return $url;
    }
}
if (!function_exists('url_news_byid')) {

    function url_news_byid($id = NULL)
    {
        $url = base_url();
        if ($id) {
            $model = model("ProductModel");
            $object = $model->find($id);
            $url = base_url("post/c$object->id.html");
        }
        return $url;
    }
}

if (!function_exists('url_library')) {

    function url_library($id)
    {
        $url = base_url();
        if ($id > 0) {
            $library_model = model("LibraryModel");
            $library = $library_model->find($id);
            $url = base_url("thu-vien/" . ($library->slug != '' ? $library->slug : str_slug($library->title_vi)) . "-c$id.html");
        }
        return $url;
    }
}

if (!function_exists('url_category')) {

    function url_category($category = NULL)
    {
        $url = base_url();
        if ($category) {
            $url = base_url("danh-muc/c$category->id.html");
        }
        return $url;
    }
}
if (!function_exists('url_category_byid')) {

    function url_category_byid($id = NULL)
    {
        $url = base_url();
        if ($id) {
            $model = model("CategoryModel");
            $object = $model->find($id);
            $url = base_url("danh-muc/c$object->id.html");
        }
        return $url;
    }
}
if (!function_exists('sync_cart')) {

    function sync_cart()
    {

        $items = array(
            'details' => array(),
            'count_product' => 0,
            'amount_product' => 0,
            'paid_amount' => 0,
            'service_fee' => -1
        );
        helper('cookie');

        $product_model = model("ProductModel");
        $area_model = model("AreaModel");

        $cart = array();
        if (get_cookie("DATA_CART") && get_cookie("DATA_CART") != "") {
            $cart = json_decode(get_cookie("DATA_CART"), true);
        }
        if (isset($cart['details']) && count($cart['details']) > 0) {
            //            echo "<pre>";
            //            print_r($cart);
            //            die();
            // $cart =
            foreach ($cart['details'] as $key => $item) {
                $data = array();
                if (!isset($item['id']) || !isset($item['qty'])) {
                    continue;
                }
                $qty = $item['qty'];
                $id = $item['id'];
                $unit_id = isset($item['unit']) ? $item['unit'] : 0;

                $product = $product_model->asObject()->find($id);
                $product = $product_model->format_product($product);
                $price_this = $product->price;
                if ($unit_id > 0) {
                    foreach ($product->units as $row) {
                        if ($row->id == $unit_id) {
                            $price_this = $row->price;
                        }
                    }
                }
                $product->qty = $qty;
                $product->unit_id = $unit_id;
                $product->amount = $qty * $price_this;
                $items['count_product'] += $qty;
                $items['amount_product'] += $qty * $price_this;

                $items['details'][] = $product;
            }
            //            echo "<pre>";
            //            print_r($items);
            //            die();
            //            $cookie = array(
            //                'name' => 'CART',
            //                'value' => json_encode($items),
            //                'secure' => TRUE
            //            );
            //            $CI->input->set_cookie($co
        }

        $items['paid_amount'] = $items['amount_product'];
        // echo "<pre>";
        // print_r($items);
        // die();
        if (get_cookie("AREA_ID") && get_cookie("AREA_ID") != "") {
            $area_id = get_cookie("AREA_ID");

            $area = $area_model->asObject()->find($area_id);
            $area_model->relation($area, array("fee"));
            if (empty($area)) {
                goto end;
            }
            if (empty($area->fee)) {
                goto end;
            }
            $fees = $area->fee;
            // echo "<pre>";
            // print_r($fees);
            // die();
            foreach ($fees as $fee) {
                $items['service_fee'] = 0;
                $min = $fee->min;
                $max = $fee->max;
                if ($min == 0 || $min == "") {
                    if ($items['paid_amount'] > $max) {
                        continue;
                    }
                } elseif ($max == 0 || $max == "") {
                    if ($items['paid_amount'] <= $min) {
                        continue;
                    }
                } elseif ($items['paid_amount'] <= $min || $items['paid_amount'] > $max) {
                    continue;
                }

                $items['service_fee'] = $fee->fee;
                $items['paid_amount'] += $items['service_fee'];
                break;
            }
        }
        end:
        return $items;
    }
}
if (!function_exists('NumberToText')) {

    function NumberToText($total)
    {
        // return (language_current());
        return "";
        if (language_current() == "english") {
            return NumberToTextEN($total) . " dong";
        } else {
            return NumberToTextVN($total);
        }
    }
}
if (!function_exists('NumberToTextVN')) {

    function NumberToTextVN($total)
    {

        $rs = "";
        $total = round($total, 0);
        $ch = array("không", "một", "hai", "ba", "bốn", "năm", "sáu", "bảy", "tám", "chín");
        $rch = array("lẻ", "mốt", "", "", "", "lăm");
        $u = array("", "mươi", "trăm", "ngàn", "", "", "triệu", "", "", "tỷ", "", "", "ngàn", "", "", "triệu");
        $nstr = (string) $total;

        $len = strlen($nstr);

        for ($i = 0; $i < $len; $i++) {
            $n[$len - 1 - $i] = substr($nstr, $i, 1);
        }
        // print_r($n);
        for ($i = $len - 1; $i >= 0; $i--) {
            if ($i % 3 == 2) // số 0 ở hàng trăm
            {
                if ($n[$i] == 0 && $n[$i - 1] == 0 && $n[$i - 2] == 0) continue; //nếu cả 3 số là 0 thì bỏ qua không đọc
            } else if ($i % 3 == 1) // số ở hàng chục
            {
                if ($n[$i] == 0) {
                    if ($n[$i - 1] == 0) {
                        continue;
                    } // nếu hàng chục và hàng đơn vị đều là 0 thì bỏ qua.
                    else {
                        $rs .= " " . $rch[$n[$i]];
                        continue; // hàng chục là 0 thì bỏ qua, đọc số hàng đơn vị
                    }
                }
                if ($n[$i] == 1) //nếu số hàng chục là 1 thì đọc là mười
                {
                    $rs .= " mười";
                    continue;
                }
            } else if ($i != $len - 1) // số ở hàng đơn vị (không phải là số đầu tiên)
            {
                if ($n[$i] == 0) // số hàng đơn vị là 0 thì chỉ đọc đơn vị
                {
                    if ($i + 2 <= $len - 1 && $n[$i + 2] == 0 && $n[$i + 1] == 0) continue;
                    $rs .= " " . ($i % 3 == 0 ? $u[$i] : $u[$i % 3]);
                    continue;
                }
                if ($n[$i] == 1) // nếu là 1 thì tùy vào số hàng chục mà đọc: 0,1: một / còn lại: mốt
                {
                    $rs .= " " . (($n[$i + 1] == 1 || $n[$i + 1] == 0) ? $ch[$n[$i]] : $rch[$n[$i]]);
                    $rs .= " " . ($i % 3 == 0 ? $u[$i] : $u[$i % 3]);
                    continue;
                }
                if ($n[$i] == 5) // cách đọc số 5
                {
                    if ($n[$i + 1] != 0) //nếu số hàng chục khác 0 thì đọc số 5 là lăm
                    {
                        $rs .= " " . $rch[$n[$i]]; // đọc số
                        $rs .= " " . ($i % 3 == 0 ? $u[$i] : $u[$i % 3]); // đọc đơn vị
                        continue;
                    }
                }
            }

            $rs .= ($rs == "" ? " " : ", ") . $ch[$n[$i]]; // đọc số
            $rs .= " " . ($i % 3 == 0 ? $u[$i] : $u[$i % 3]); // đọc đơn vị
        }
        // print_r($rs);
        if ($rs[strlen($rs) - 1] != ' ')
            $rs .= " đồng";
        else
            $rs .= "đồng";

        if (strlen($rs) > 2) {
            $rs1 = substr($rs, 0, 2);
            $rs1 = strtoupper($rs1);
            $rs = substr($rs, 2);
            $rs = $rs1 . $rs;
        }
        $rs = trim($rs);
        $rs = str_replace("lẻ,", "lẻ", $rs);
        $rs = str_replace("mươi,", "mươi", $rs);
        $rs = str_replace("trăm,", "trăm", $rs);
        $rs = str_replace("mười,", "mười", $rs);

        return $rs;
    }
}

if (!function_exists('NumberToTextJP')) {

    function NumberToTextJP($total)
    {

        $rs = "";
        $total = round($total, 0);
        $ch = array("ゼロ", "一", "ニ", "三", "四", "五", "六", "七", "八", "九");
        $rch = array("十", "一", "", "", "", "五");
        $u = array("", "十", "百", "千", "", "", "百万", "", "", "十億", "", "", "千", "", "", "百万");

        $nstr = (string) $total;

        $len = strlen($nstr);

        for ($i = 0; $i < $len; $i++) {
            $n[$len - 1 - $i] = substr($nstr, $i, 1);
        }
        // print_r($n);
        for ($i = $len - 1; $i >= 0; $i--) {
            if ($i % 3 == 2) // số 0 ở hàng trăm
            {
                if ($n[$i] == 0 && $n[$i - 1] == 0 && $n[$i - 2] == 0) continue; //nếu cả 3 số là 0 thì bỏ qua không đọc
            } else if ($i % 3 == 1) // số ở hàng chục
            {
                if ($n[$i] == 0) {
                    if ($n[$i - 1] == 0) {
                        continue;
                    } // nếu hàng chục và hàng đơn vị đều là 0 thì bỏ qua.
                    else {
                        $rs .= " " . $rch[$n[$i]];
                        continue; // hàng chục là 0 thì bỏ qua, đọc số hàng đơn vị
                    }
                }
                if ($n[$i] == 1) //nếu số hàng chục là 1 thì đọc là mười
                {
                    $rs .= " 十";
                    continue;
                }
            } else if ($i != $len - 1) // số ở hàng đơn vị (không phải là số đầu tiên)
            {
                if ($n[$i] == 0) // số hàng đơn vị là 0 thì chỉ đọc đơn vị
                {
                    if ($i + 2 <= $len - 1 && $n[$i + 2] == 0 && $n[$i + 1] == 0) continue;
                    $rs .= " " . ($i % 3 == 0 ? $u[$i] : $u[$i % 3]);
                    continue;
                }
                if ($n[$i] == 1) // nếu là 1 thì tùy vào số hàng chục mà đọc: 0,1: một / còn lại: mốt
                {
                    $rs .= " " . (($n[$i + 1] == 1 || $n[$i + 1] == 0) ? $ch[$n[$i]] : $rch[$n[$i]]);
                    $rs .= " " . ($i % 3 == 0 ? $u[$i] : $u[$i % 3]);
                    continue;
                }
                if ($n[$i] == 5) // cách đọc số 5
                {
                    if ($n[$i + 1] != 0) //nếu số hàng chục khác 0 thì đọc số 5 là lăm
                    {
                        $rs .= " " . $rch[$n[$i]]; // đọc số
                        $rs .= " " . ($i % 3 == 0 ? $u[$i] : $u[$i % 3]); // đọc đơn vị
                        continue;
                    }
                }
            }

            $rs .= ($rs == "" ? " " : ", ") . $ch[$n[$i]]; // đọc số
            $rs .= " " . ($i % 3 == 0 ? $u[$i] : $u[$i % 3]); // đọc đơn vị
        }
        // print_r($rs);
        if ($rs[strlen($rs) - 1] != ' ')
            $rs .= " đồng";
        else
            $rs .= "đồng";

        if (strlen($rs) > 2) {
            $rs1 = substr($rs, 0, 2);
            $rs1 = strtoupper($rs1);
            $rs = substr($rs, 2);
            $rs = $rs1 . $rs;
        }
        $rs = trim($rs);
        $rs = str_replace("lẻ,", "lẻ", $rs);
        $rs = str_replace("mươi,", "mươi", $rs);
        $rs = str_replace("trăm,", "trăm", $rs);
        $rs = str_replace("mười,", "mười", $rs);

        return $rs;
    }
}

if (!function_exists('NumberToTextEN')) {

    function NumberToTextEN($number)
    {

        if (($number < 0) || ($number > 999999999)) {
            throw new Exception("Number is out of range");
        }

        $Gn = floor($number / 1000000);
        /* Millions (giga) */
        $number -= $Gn * 1000000;
        $kn = floor($number / 1000);
        /* Thousands (kilo) */
        $number -= $kn * 1000;
        $Hn = floor($number / 100);
        /* Hundreds (hecto) */
        $number -= $Hn * 100;
        $Dn = floor($number / 10);
        /* Tens (deca) */
        $n = $number % 10;
        /* Ones */

        $res = "";

        if ($Gn) {
            $res .= NumberToTextEN($Gn) .  "Million";
        }

        if ($kn) {
            $res .= (empty($res) ? "" : " ") . NumberToTextEN($kn) . " Thousand";
        }

        if ($Hn) {
            $res .= (empty($res) ? "" : " ") . NumberToTextEN($Hn) . " Hundred";
        }

        $ones = array("", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", "Nineteen");
        $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty", "Seventy", "Eigthy", "Ninety");

        if ($Dn || $n) {
            if (!empty($res)) {
                $res .= " and ";
            }

            if ($Dn < 2) {
                $res .= $ones[$Dn * 10 + $n];
            } else {
                $res .= $tens[$Dn];

                if ($n) {
                    $res .= "-" . $ones[$n];
                }
            }
        }

        if (empty($res)) {
            $res = "zero";
        }

        return $res;
    }
}
