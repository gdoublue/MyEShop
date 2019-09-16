<?php

namespace  app\admin\logic;
use Request;
use Db;
class GoodsLogic
{
    //获取商品的顶级分类
    public function getGoodsCategoryList($where)
    {
        $categoryList = Db::name("category")->where($where)->select();
        return $categoryList;
    }
    //添加商品
    public function addGoods($product)
    {
        //构建添加数据
        $data = [
            "goods_name" => $product["title"],  //商品名称
            "category_id" => $product["categoryId"],  //分类的id
            "market_price" => $product["market_price"],  //市场价格
            "price"  => $product["price"],  //销售价格
            "point_exchange_type" => $product["point_exchange_type"],  //积分兑换类型
            "point_exchange" =>$product["integration_available_use"], //积分兑换
            "give_point" => $product["integration_available_give"],     //赠送积分
            "shipping_fee" => $product["shipping_fee"],  //物流费用
            "shipping_fee_id" => $product["shipping_fee_id"],  //物流模板
            "stock" => $product["stock"],  //商品库存
            "max_buy" => $product["max_buy"],  //限购
            "min_stock_alarm" => $product["minstock"], // 库存预警
            "picture" => $product["picture"],  //商品主图
            "keywords" => $product["key_words"],  //关键字
            "introduction" => $product["introduction"],  //促销语
            "description"  => $product["description"],   //商品详情
            "QRcode" => $product["qrcode"],   //商品二维码
            "code" => $product['code'],   //商品编号
            "state" => $product["is_sale"],  //是否上架
            "create_time" => time(),    //商品添加时间
            "sort" => $product["sort"], //排序
            "img_id_array" => $product["imageArray"], //图片集合
            "shipping_fee_type" => $product["shipping_fee_type"], //计费类型
        ];
        $row = Db::name("goods")->insert($data);
        if ($row) {
            return ["state"=>1];
        } else {
            return ["state"=>0];
        }
    }

    //获取商品列表
    public function getGoodsList()
    {
        //请求的是第几页
        $page_index = Request::param("page_index");
        //每页获取多少条
        $page_size = Request::param("page_size");
        //查询商品
        $goodsList = Db::name("goods")->page($page_index, $page_size)->select();
        foreach ($goodsList as &$value) {
            //时间格式的转换
            $value["create_time"] = date("Y-m-d H:i:s", $value['create_time']);
            //获取图片的id
            $pic_id = (int)$value["picture"];
            //查询图片的地址
            $pic_addr =  Db::name("picture")->field("pic_addr")->where("id", $pic_id)->find();
            $value["pic_addr"] = "/uploads/".$pic_addr["pic_addr"];
        }
        //获取商品的总数量
        $counts = Db::name("goods")->count();
        //获取总页数
        $page_count = $counts/$page_size;
        if ($page_count < 1) {
            $page_count = 1;
        } else {
            $page_count = floor($page_count) + 1;
        }

        //返回数据
        return [
            "page_count" => $page_count,  //商品的总页数
            "data" => $goodsList, //商品数据
            "total_count" => $counts,  //商品的总数量
        ];
    }
}