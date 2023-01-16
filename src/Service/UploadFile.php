<?php

namespace Zperfe\Common\Service;

use Illuminate\Support\Facades\Http;
use Zperfe\Common\CommonService;

class UploadFile extends CommonService {

    /**
     * 文件上传
     * @param $file 文件
     * @param string $object_key 为空随机名称
     * @param string $path 路径
     * @return array
     */
    public function putFile($file, $path = '')
    {
        $url = 'file/upload';        
        $params['path'] = $path;
        $params['unique_name'] = 1;
        $params['object_key'] = $file->getClientOriginalName();

        $query = $this->buildParam($params);

        $response = Http::withOptions([
            "verify" => false,
        ])->attach('file', fopen($file->getPathname(), 'r'))->post($this->service_url . $url, $query);

        return $this->response($response);
    }

    /**
     * 上传文件流
     * @param string $body 上传对象的内容
     * @param string $objectKey 文件名
     * @param string $contentType 文件MIME类型
     * @param bool $unique_name 生成唯一名称
     * @param string $path 路径
     * @return bool|array
     */
    public function putFileBody($body, $objectKey, $contentType, $unique_name = false, $path = '')
    {
        $url = 'file/upload';
        $params = [
            'body' => $body,
            'object_key' => $objectKey,
            'content_type' => $contentType,
            'path' => $path,
        ];

        if ($unique_name) {
            $params['unique_name'] = 1;
        }

        $query = $this->buildParam($params);

        $response = Http::withOptions([
            "verify" => false,
        ])->post($this->service_url . $url, $query);

        return $this->response($response);
    }
    
}