<?php
if (!function_exists('load_inputfile')) {

    function load_inputfile(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/fileinput/css/fileinput.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/fileinput/css/theme_fileinput.css");

        array_push($data['javascript_tag'], base_url() . "/assets/lib/fileinput/js/fileinput.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/fileinput/js/sortable.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/fileinput/js/theme_fileinput.js");
    }
}


if (!function_exists('load_datatable')) {

    function load_datatable(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/datatables/datatables.min.css");

        array_push($data['javascript_tag'], base_url() . "/assets/lib/datatables/datatables.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/datatables/jquery.highlight.js");
    }
}


if (!function_exists('load_autonumberic')) {

    function load_autonumberic(&$data)
    {
        array_push($data['javascript_tag'], base_url() . "/assets/lib/autoNumberic/autoNumberic.js");
    }
}
if (!function_exists('load_toast')) {

    function load_toast(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/toast/jquery.toast.min.css");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/toast/jquery.toast.min.js");
    }
}
if (!function_exists('load_fancybox')) {

    function load_fancybox(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/fancybox/jquery.fancybox.min.css");

        array_push($data['javascript_tag'], base_url() . "/assets/lib/fancybox/jquery.fancybox.min.js");
    }
}
if (!function_exists('load_daterangepicker')) {

    function load_daterangepicker(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/daterangepicker/daterangepicker.css");

        array_push($data['javascript_tag'], base_url() . "/assets/lib/daterangepicker/moment.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/daterangepicker/daterangepicker.js");
    }
}

if (!function_exists('load_easyzoom')) {

    function load_easyzoom(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/easyzoom/css/easyzoom.css");

        array_push($data['javascript_tag'], base_url() . "/assets/lib/easyzoom/js/easyzoom.js");
    }
}

if (!function_exists('load_slick')) {

    function load_slick(&$data)
    {
        array_push($data['javascript_tag'], base_url() . "/assets/lib/slick/slick.min.js");
    }
}


if (!function_exists('load_easyResponsiveTabs')) {

    function load_easyResponsiveTabs(&$data)
    {
        array_push($data['javascript_tag'], base_url() . "/assets/lib/easy_responsive_tabs/js/easyResponsiveTabs.js");
    }
}

if (!function_exists('load_swiper')) {

    function load_swiper(&$data)
    {
        array_push($data['javascript_tag'], base_url() . "/assets/lib/swiper/jquery.touchSwiper.js");
    }
}



if (!function_exists('load_froala_view')) {

    function load_froala_view(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/froala_style.min.css");
    }
}




if (!function_exists('load_editor')) {

    function load_editor(&$data)
    {

        //        array_push($data['stylesheet_tag'], "https://cdn.jsdelivr.net/npm/froala-editor@2.9.3/css/froala_editor.min.css");
        //        array_push($data['stylesheet_tag'], "https://cdn.jsdelivr.net/npm/froala-editor@2.9.3/css/froala_style.min.css");
        //        array_push($data['javascript_tag'], "https://cdn.jsdelivr.net/npm/froala-editor@2.9.3/js/froala_editor.min.js");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/froala_editor.min.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/froala_style.min.css");
        /////////// Plugin
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/char_counter.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/code_view.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/colors.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/draggable.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/emoticons.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/file.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/fullscreen.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/image.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/image_manager.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/line_breaker.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/quick_insert.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/table.css");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/video.css");

        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/froala_editor.min.js");
        /////////// Plugin
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/align.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/char_counter.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/colors.min.js");
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/froala_editor/plugins/file.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/entities.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/font_size.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/fullscreen.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/image.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/image_manager.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/link.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/lists.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/paragraph_format.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/paragraph_style.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/quick_insert.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/save.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/url.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/froala_editor/plugins/video.min.js");
    }
}


if (!function_exists('load_sort_nest')) {

    function load_sort_nest(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/sortable/sortable.css");
        /////////// Plugin
        //        array_push($data['javascript_tag'], base_url() . "public/admin/vendor/shortable-nestable/Sortable.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/sortable/jquery.mjs.nestedSortable.js");
        //        array_push($data['javascript_tag'], base_url() . "public/admin/vendor/shortable-nestable/jquery.nestable.js");
    }
}


if (!function_exists('load_chossen')) {

    function load_chossen(&$data)
    {
        array_push($data['stylesheet_tag'], base_url() . "/assets/lib/chosen/chosen.min.css");
        /////////// Plugin
        //        array_push($data['javascript_tag'], base_url() . "public/admin/vendor/shortable-nestable/Sortable.min.js");
        array_push($data['javascript_tag'], base_url() . "/assets/lib/chosen/chosen.jquery.js");
        //        array_push($data['javascript_tag'], base_url() . "public/admin/vendor/shortable-nestable/jquery.nestable.js");
    }
}
