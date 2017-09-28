<h2>订单编号:<?=$order->out_trade_no?></h2>
<p>支付金额:<?=$order->total_fee/100?></p>
<p>微信扫码支付:<img src="/wechat/qr?content=<?=$code_url?>"/></p>