<?php
namespace Zhpefe\CommonService\Service;

use Illuminate\Support\Facades\Http;
use Zhpefe\CommonService\CommonService;

class OrderPay extends CommonService {
    
    /**
     * Undocumented function
     *
     * @param string|int $channel_id 支付渠道编码或ID
     * @param string $app_trade_no 支付订单号
     * @param string $description 产品说明
     * @param int $amount_total 订单金额,必须为整数,单位为分,最小单位.
     * @param array $params 订单创建附加参数
     * @param string $amount_currency 币种,默认THB
     * @param array $attach 其他参数,会随支付结果下发给应用.
     * @return array
     */
    public function pay(int|string $channel_id, string $app_trade_no, string $description, int $amount_total, array $params = [], string $amount_currency = 'THB', array $attach = []) {
        $url = 'order/create';

        $query = [
            "channel_id" => $channel_id,
            "app_trade_no" => $app_trade_no,
            "description" => $description,
            "amount_total" => $amount_total,
            "params" => json_encode($params),
            "amount_currency" => $amount_currency,
            "attach" => json_encode($attach),
        ];

        $query = $this->buildParam($query);

        $response = Http::withOptions([
            "verify" => false,
        ])->post($this->service_url . $url, $query);

        return $this->response($response);
    }
}