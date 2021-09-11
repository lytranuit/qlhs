<?php

namespace App\Libraries;

class SideBarWidget
{
    private $data = array();
    public function __construct()
    {
    }
    public function product()
    {

        $my_region = area_current();
        $product_model = model("ProductModel");

        $products = $product_model->where("status = 1 and is_pet = 1 and FIND_IN_SET('$my_region',region)")->asObject()->findAll(8);
        foreach ($products as &$product) {
            $product_model->format_product($product);
        }
        $this->data['products'] = $products;
        // echo "<pre>";
        // print_r($list_category);
        // die();
        return view("frontend/widget/sidebar/" . __FUNCTION__, $this->data);
    }
    public function hot_news()
    {

        $tag_model = model("TagModel");
        $news_model = model("NewsModel");
        $this->data['info'] = $tag_model->where("id", 4)->asObject()->first();
        $news_info = $news_model->get_news_by_tag(4, 4);
        $this->data['info']->news = $news_info['news'];
        // $tags = array_map(function ($item) {
        //     return $item->tag_id;
        // }, $this->data['info']->tags);
        //echo "<pre>";
        //print_r($this->data['info']->news);
        //die();
        // $this->data['news'] = $news_model->get_news_related($id, $tags);

        return view("frontend/widget/sidebar/" . __FUNCTION__, $this->data);
    }
}
