<?php


namespace app\home\controller;

use think\Controller;
use Db,Request,Session;
use payment\weixin\Wchat;


class Pay extends Controller
{
    //显示支付页面
    public function index()
    {
        $mid = Session::get("mid");
        if ($mid) {
            //查询购物车获取用户的商品数据
            $cartData =  Db::name("cart")->where("member_id",$mid)->select();
            $total = 0;  //总金额
            foreach ($cartData as $cart) {
                //获取商品的id
                $goods_id = $cart["goods_id"];
                $goodsData = Db::name("goods")->field("goods_id,introduction,price,picture")->where("goods_id",$goods_id)->find();
                //总金额
                $sum = $cart["goods_number"]*$goodsData["price"];
                $arr["goods_id"] = $goods_id;  //商品id
                $arr["goods_price"] = $goodsData["price"];  //商品价格
                $arr["sum"] = $sum;   // 每件商品的总金额
                $arr["name"] = $goodsData["introduction"];  //商品名称
                $arr["count"] = $cart["goods_number"];  //单件商品的数量
                $arr["cart_id"] = $cart["id"];   //购物车id
                $total += $sum;
                $goods[] = $arr;
//                $totalCount += $arr["count"];
            }
            //获取用户的地址信息
            $memberAddress = Db::name("member_address")->where("uid",$mid)->where("is_default",1)->find();
            //构建订单数据
            $data = [
                "member_id" => $mid,  //用户id
                "addtime" => time(), //下单时间
                "pay_status" => "否",  //支付状态
                "total_price" => $total, //总金额
                "shr_name" => $memberAddress["consigner"],  //收件人姓名
                "shr_tel" => $memberAddress["mobile"],   //电话
                "shr_province" => $memberAddress["province"],  //省id
                "shr_city" => $memberAddress["city"],  //市id
                "shr_area" => $memberAddress["district"],  //区域id
                "shr_address" => $memberAddress["address"],  //详细地址
                "post_status" => 0,  //发货状态
            ];

            $orderId =  Db::name("order")->insertGetId($data);

            foreach ($goods as $g) {
                //构建添加到订单商品表里的数据
                $data = [
                    "order_id" => $orderId ,
                    "goods_id" => $g["goods_id"],
                    "goods_number" => $g["count"] ,
                    "price"=> $g["goods_price"] ,
                ];
                $row = Db::name("order_goods")->insert($data);
                if (!$row) {
                    $this->error("订单添加失败");
                }
            }
        } else {
            header("location: http://www.jayhui.cn/home/login/login");
            exit;
        }

        $this->assign("total",$total);
        $this->assign("consigner",$memberAddress["consigner"]);
        return $this->fetch();
    }

    //微信支付
    public function wchatpay()
    {
        $orderId = Request::param("orderId");
        $wchat = new Wchat();
        $path = $wchat->wchatpay($orderId);
        return json(["code"=>1,"path"=>$path]);
    }

    public function notify()
    {

    }

    //阿里支付
    public function alipay()
    {
        $ali = new Alipay();
        $ali->pay(1);
    }
}
