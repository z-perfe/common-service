<?php
namespace Zperfe\CommonService;

class CommonService {

    protected $app_id;
    protected $app_secret;
    protected $service_url;

    public function __construct()
    {
        $config = config('common_service');
        $this->app_id = $config['app_id'];
        $this->app_secret = $config['app_secret'];
        $service_url = rtrim($config['service_url'], '/');
        $this->service_url = "{$service_url}/api/{$config['service_version']}/";
    }    

    public function response($response) {
        $response = json_decode($response, true);
        
        if (isset($response['status'])) {
            return $response;
        } 

        return [
            'status' => 500,
            'message' => 'Service Error',
            'code' => 'Service Error',
        ];
    }

    /**
     * 去掉参数中的空值和不参与加密的键
     * @param array $data
     * @return array
     */
    public function paramFilter ($data) {
        $param = [];
        foreach ((array)$data as $key=> $val) {
            if($key == 'sign' || $val == "" || $val == null)
                continue;
            else
                $param[$key] = $val;
        }
        return $param;
    }
    /**
     * 生成 sign
     * @param string $secret
     * @param array $param
     * @return string
     */
    public function getSign($param) {
        $param = $this->paramFilter($param);
        ksort($param);
        reset($param);
        $params_str = http_build_query($param);
        return md5($params_str . $this->app_secret);
    }
    /**
     * 验证 sign
     * @param string $secret
     * @param array $param
     * @param string $sign
     * @return boolean
     */
    public function verifySign($param, $sign = '')
    {
        if (isset($param['sign'])) {
            $sign = $param['sign'];
        }
        $md5 = $this->getSign($param);
        return $md5 === $sign;
    }
    /**
     * 验证 param 是否合规
     * @param string $secret
     * @param array $param
     * @param string $sign
     * @return boolean
     */
    public function verifyParam($param)
    {
        if (! (array_key_exists('sign', $param) && $param['sign']) ) {
            return false;
        }
        if (! (array_key_exists('timestamp', $param) && is_numeric($param['timestamp'])) ) {
            return false;
        }
        if (time() - $param['timestamp'] > 60 * 10) {
            return false;
        }
        return $this->verifySign($param, $param['sign']);
    }
    /**
     * 组装加密数据
     * @param string $secret
     * @param array $param
     * @return array
     */
    public function buildParam(array $param) {
        $param['timestamp'] = time();
        $param['app_id'] = $this->app_id;
        $param['sign'] = $this->getSign($param);
        return $param;
    }
}