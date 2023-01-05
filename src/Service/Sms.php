<?php
namespace Zhpefe\CommonService\Service;

use Illuminate\Support\Facades\Http;
use Zhpefe\CommonService\CommonService;

class Sms extends CommonService {
    
    /**
     * 发送短信
     *
     * @param string $action 动作名称
     * @param string $mobile 手机号码
     * @param array $params 短信参数
     * @return array
     */
    public function send(string $action, string $mobile, array $params = []) {
        $url = 'sms/send';

        $query = [
            "action" => $action,
            "mobile" => $mobile,
            "params" => $params,
        ];

        $query = $this->buildParam($query);

        $response = Http::withOptions([
            "verify" => false,
        ])->post($this->service_url . $url, $query);

        return $this->response($response);
    }

    /**
     * 获得可用的动作列表
     *
     * @return array
     */
    public function getAction() {
        $url = 'sms/get_action';

        $query = $this->buildParam([]);

        $response = Http::withOptions([
            "verify" => false,
        ])->post($this->service_url . $url, $query);

        return $this->response($response);
    }
}