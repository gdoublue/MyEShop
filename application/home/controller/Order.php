<?php

namespace app\home\controller;

use think\Controller;
use Db,Request,Session;

class Order extends Controller
{
    //显示订单列表
    public function orderlist()
    {
        //获取用户的id
        $mid = Session::get("mid");
        if ($mid) {
            //获取用户的地址列表
            $address = Db::name("member_address")->where("uid",$mid)->select();
            foreach ($address as &$addr) {
                //省id
                $provinceId = $addr["province"];
                $province =  Db::name("region")->field("region_name")->where("id",$provinceId)->find();
                $province = $province["region_name"];
                //市id
                $cityId = $addr["city"];
                $city =  Db::name("region")->field("region_name")->where("id",$cityId)->find();
                $city = $city["region_name"];
                //区id
                $districtId = $addr["district"];
                $district =  Db::name("region")->field("region_name")->where("id",$districtId )->find();
                $district = $district["region_name"];
                $detailAddr =  $province." ". $city." ".$district." ".$addr["address"];
                $addr["address"] = $detailAddr;
            }

            //获取商品的列表
            //查询购物车 获取用户的商品数据
            $cartData = Db::name("cart")->where("member_id",$mid)->select();
            //定义二维数组存储商品的信息
            $goods = [];
            $total = 0;  //所有商品的总金额
            //所有商品的数量
            $totalCount = 0;
            foreach ($cartData as $cart) {
                //获取商品的id
                $goods_id = $cart["goods_id"];
                $goodsData = Db::name("goods")->field("goods_id,introduction,price,picture")->where("goods_id",$goods_id)->find();
                //根据图片的id
                $picid = $goodsData["picture"];
                $picData = Db::name("picture")->field("pic_addr")->where("id",$picid)->find();
                //图片的地址
                $pic_addr = "/uploads/".$picData["pic_addr"];
                //总金额
                $sum = $cart["goods_number"]*$goodsData["price"];
                $arr["goods_id"] = $goods_id;  //商品id
                $arr["goods_price"] = $goodsData["price"];  //商品价格
                $arr["pic_addr"] = $pic_addr;  //图片地址
                $arr["sum"] = $sum;   // 每件商品的总金额
                $arr["name"] = $goodsData["introduction"];  //商品名称
                $arr["count"] = $cart["goods_number"];  //单件商品的数量
                $arr["cart_id"] = $cart["id"];   //购物车id
                $total += $sum;
                $goods[] = $arr;
                $totalCount += $arr["count"];
            }

        } else {
            header("location: http://www.jayhui.cn/home/login/login");
            exit;
        }
        $this->assign("total",$total);
        $this->assign("totalCount",$totalCount);
        $this->assign("goodsData",$goods);
        $this->assign("address",$address);
        return $this->fetch();
    }

    //获取省数据
    public function getProvince()
    {
        $province =  Db::name("region")->field("id,region_name")->where("parent_id",1)->select();
        return json($province);
    }

    //获取市数据
    public function getCity()
    {
        //获取省id
        $provinceId = Request::param("province_id");
        $city =  Db::name("region")->field("id,region_name")->where("parent_id",  $provinceId)->select();
        return json($city);
    }

    //获取区域数据
    public function getDistrict()
    {
        //获取市id
        $cityId = Request::param("city_id");
        $district =  Db::name("region")->field("id,region_name")->where("parent_id",  $cityId)->select();
        return json($district);

    }

    //保存用户收货地址
    public function operationaddress()
    {
        //构建添加数据
        $data = [
            "uid" => Session::get("mid"),
            "consigner" => Request::param("consigner"),
            "mobile" => Request::param("mobile"),
            "province" => Request::param("province"),
            "city" => Request::param("city"),
            "district" => Request::param("district"),
            "address" => Request::param("address"),
        ];
        //添加数据
        $row = Db::name("member_address")->insert($data);
        if ($row) {
            return json(["message"=>"保存成功"]);
        }

    }

    //修改默认地址
    public function updateaddressdefault()
    {
        //获取地址id
        $addrId = Request::param("id");
        $mid = Session::get("mid");
        $row = Db::name("member_address")->where("uid",$mid)->data(["is_default"=>0])->update();
        if ($row) {
            $row = Db::name("member_address")->where("id",$addrId)->data(["is_default"=>1])->update();
            if ($row) {
                return json(["code"=>1,"message"=>"操作成功"]);
            } else {
                return json(["code"=>0,"message"=>"操作失败"]);
            }
        } else {
            return json(["code"=>0,"message"=>"修改失败"]);
        }
    }
}