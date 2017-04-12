<?php
namespace cms;

use think\Url;
use think\exception\HttpResponseException;
use cms\traits\InstanceTrait;

class Response
{
    /**
     * 实例Trait
     */
    use InstanceTrait;

    /**
     * API返回
     *
     * @param integer $code            
     * @param string $msg            
     * @param mixed $data            
     */
    public function api($code, $msg = '', $data = '')
    {
        $this->json([
            'code' => $code,
            'msg' => $msg,
            'data' => $data
        ]);
    }

    /**
     * 返回跳转
     *
     * @param array $url            
     * @param boolean $build            
     * @param array $header            
     * @param array $options            
     * @return void
     */
    public function redirect($url, $build = true, $header = [], $options = [])
    {
        $header['Location'] = $build ? $this->buildUrl($url) : $url;
        $this->data('', '', 302, $header, $options);
    }

    /**
     * 返回Xml
     *
     * @param string $xml            
     * @param array $header            
     * @param array $options            
     * @return void
     */
    public function xml($xml, $header = [], $options = [])
    {
        $this->data($xml, 'xml', 200, $header, $options);
    }

    /**
     * 返回Json
     *
     * @param array $json            
     * @param array $header            
     * @param array $options            
     * @return void
     */
    public function json($json, $header = [], $options = [])
    {
        $this->data($json, 'json', 200, $header, $options);
    }

    /**
     * 返回结果
     *
     * @param mixed $data            
     * @param string $type            
     * @param array $header            
     * @param array $options            
     * @return void
     */
    public function data($data, $type = 'auto', $code = 200, $header = [], $options = [])
    {
        $type == 'auto' && $type = is_array($data) ? 'json' : '';
        $response = \think\Response::create($data, $type, $code, $header, $options);
        throw new HttpResponseException($response);
    }

    /**
     * 构造Url
     *
     * @param string $url            
     * @return string
     */
    protected function buildUrl($url)
    {
        if (strpos($url, '://') || 0 === strpos($url, '/')) {
            return $url;
        } elseif ($url === '') {
            return $url;
        } else {
            return Url::build($url);
        }
    }
}