<?php
namespace app\admin\controller;

use  app\admin\controller\Adminbase;
use app\admin\logic\GoodsLogic;
use Request;
use app\admin\model\Picture;
use Db;

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\LabelAlignment;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Response\QrCodeResponse;

/*//定义商品类

*/
class Goods extends Adminbase
{
    //goodlist
    public function  goodsList()
    {
        if (Request::isPost()) {
            $goodsLogic = new GoodsLogic;
            $result = $goodsLogic->getGoodsList();
            return json($result);exit;
        }
        return $this->fetch();
    }

//add goods
    public function addGoods()
    {
        if (Request::isAjax()) {
            $goodsInfo = Request::param("product");

            $goodsLogic = new GoodsLogic;
            $result = $goodsLogic->addGoods($goodsInfo);
            return json($result);
        }
        return $this->fetch();
    }
    public function dialogSelectCategory()
    {
        //实例化商品逻辑类
        $goodsLogic = new GoodsLogic;
        $categoryOne = $goodsLogic->getGoodsCategoryList(['pid'=>0]);
        if (Request::isAjax())
        {
            $id =   Request::param('id');
            $categoryOne = $goodsLogic->getGoodsCategoryList(["pid"=>$id]);
            return json([
                'data'=>$categoryOne,
                'id'  =>$id,
            ]);
        }
        //模板赋值
        $this->assign("categoryOne", $categoryOne);
        return $this->fetch();

    }

    //图片上传
    public function upload()
    {
        //获取文件上传的信息
        $file = request()->file("file_upload");
        //实例化图片模型
        $picture = new Picture;
        $result = $picture->upload($file);
        //将数据返回给前端
        return json($result);

    }
    public function addGoods2()
    {
        //获取cid
        $cid = Request::param("cid");
        $category = \Db::name("category")->where("id", $cid)->find();
        $this->assign("category", $category);
        $this->fetch("add_goods");
    }
    public function getchildcategory()
    {
        //获取子类id
        $categoryId = Request::param("categoryID");
        $category = Db::name("category")->where("pid", $categoryId)->select();
        return json($category);

    }

    //将分类数据数据写入cookie中
    public function selectcategetdata()
    {
        $goods_category_id = request()->post("goods_category_id", ''); // 商品类目用
        $goods_category_name = request()->post("goods_category_name", ''); // 商品类目名称显示用
        $goods_attr_id = request()->post("goods_attr_id", ''); // 关联商品类型ID
        $quick = request()->post("goods_category_quick", ''); // JSON格式
        setcookie("goods_category_id", $goods_category_id, time() + 3600 * 24);
        setcookie("goods_category_name", $goods_category_name, time() + 3600 * 24);
        setcookie("goods_attr_id", $goods_attr_id, time() + 3600 * 24);
        setcookie("goods_category_quick", $quick, time() + 3600 * 24);
    }
    //更新二维码
    public function updateGoodsQrcode()
    {
        //获取商品的id
        $goodsId = Request::param("goods_id");
        foreach ($goodsId as $id){
            //设置商品详情的地址
            $url = "http://www.jayhui.cn/wap/goods/goodsDetail/id/$id";
//           $url =  url("wamp/goods/goodsDetail",["id"=>$id]);

            $qrCode = new QrCode($url);
            //定义二维码存储地址
            $qrcodeAddr = "qrcode/".md5(time()).".png";
            $qrCode->writeFile($qrcodeAddr);
            //把二维码地址写入数据库中
            $row = Db::name("goods")->where("goods_id",$id)->update(["QRcode"=>$qrcodeAddr]);
            if (!$row) {
                return json(["code" => 0]);
            }
        }
        return json(['code' => 1]);
    }




}



