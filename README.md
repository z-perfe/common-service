# common-service
### 安装
> 适用于 laravel PHP 7.4+

> composer require z-perfe/common-service
### 发布配置文件
> php artisan vendor:publish --tag=common_service

> config/common_service.php文件中,配置APP_ID等参数

## 短信服务
```
use Zperfe\Common\Service\Sms;

$sms = new Sms();

// 发送短信
$sms->send(string $action, string $mobile, array $params = []);

// 获得可用的动作列表
$sms->getAction();
```
`` $sms->send 参数说明 ``
|参数名|类型|必传|说明|
|:---:|:---|:---|:---|
|action|string|是|短信的动作名称|
|mobile|string|是|手机号码|
|params|array|否|动作所需替换的参数|
*************
## 支付服务
```
use Zperfe\Common\Service\OrderPay;

$pay = new OrderPay();

// 订单支付
$pay->pay(int|string $channel_id, string $app_trade_no, string $description, int $amount_total, array $params = [], string $amount_currency = 'THB', array $attach = []);

// 获得可用的支付渠道
$pay->getChannels();
```
`` $pay->pay 参数说明 ``
|参数名|类型|必传|说明|
|:---:|:---|:---|:---|
|channel_id|string or int|是|使用的渠道代码或ID|
|app_trade_no|string|是|订单号码, 请保持唯一|
|description|string|是|订单的商品名称|
|amount_total|int|是|订单的金额,单位为最小单位分,需一个正整数|
|params|array|否|部分渠道支付创建时需要的其他参数|
|amount_currency|string|否|支付币种,默认THB|
|attach|array|否|订单附带的其他参数,会随支付结果下发至应用|
*************

``` 
成功的响应 
{
    "status": 1,
    "message": "SUCCESS",
    "data": []
}
```

``` 
失败的响应 
{
    "status": 400,
    "message": "错误的params, 缺乏必须的键：code",
    "code": "BadRequest"
}
```

```
支付结果下发通知
{
	"result_code": "SUCCESS",
	"ordersn": "",
	"app_trade_no": "",
	"notify_type": "PAY",
	"amount_total": '',
	"timestamp": '',
	"sign": ""
}
```
`` 支付结果下发通知的参数说明 ``
|参数名|类型|说明|
|:---:|:---|:---|
|result_code|string|支付结果, SUCCESS为成功, FAIL为支付失败|
|ordersn|string|CommonService平台产生的订单号码|
|app_trade_no|string|应用的订单号码|
|amount_total|int|订单的金额,单位为最小单位分,是一个正整数|
|notify_type|string|通知类型, PAY为支付结果通知,REFUND为退款结果通知|
|timestamp|string|下发结果的时间戳|
|sign|string|签名, 请一定对支付结果通知进行签名校验|

*************
## 支付通知的校验方法
```
use Zperfe\Common\Service\OrderPay;

$input = $request->all();
$pay = new OrderPay();

// 校验签名
if ($pay->verifySign($input)) {
    // 签名通过
    if ($input['timestamp'] - time() > 60 * 60 * 24) {
        // 可以对下发的时间戳进行判断...
    }
}
```