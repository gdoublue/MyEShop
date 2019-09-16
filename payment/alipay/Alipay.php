<?php

namespace payment\alipay;

use Db;
class Alipay
{
    private $config;
    public function __construct()
    {
        $config = require __DIR__.'/config.php';
        $this->config = $config;
        require __DIR__.'/pagepay/service/AlipayTradeService.php';
        require __DIR__.'/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';
    }

    public function pay($orderId)
    {
        $order = Db::name("order")->where("id",$orderId)->find();
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $order["out_trade_no"];

        //订单名称，必填
        $subject = "六星商城";

        //付款金额，必填
        $total_amount = $order["total_price"];

        //商品描述，可空
//        $body = trim($_POST['WIDbody']);

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
//        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($this->config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
         */
        $response = $aop->pagePay($payRequestBuilder,$this->config['return_url'],$this->config['notify_url']);

        //输出表单
        var_dump($response);
    }
}