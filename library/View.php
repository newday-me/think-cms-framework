<?php
namespace cms;

use think\Config;
use think\Request;
use cms\Response;
use cms\Minify;

class View extends \think\View
{

    /**
     * 成功时渲染模板
     *
     * @var unknown
     */
    protected $successTemplate;

    /**
     * 失败时渲染模板
     *
     * @var unknown
     */
    protected $errorTemplate;

    /**
     * 是否压缩输出
     *
     * @var unknown
     */
    protected $isMinify = false;

    /**
     *
     * {@inheritdoc}
     *
     * @see \think\View::fetch()
     */
    public function fetch($template = '', $vars = [], $replace = [], $config = [], $renderContent = false)
    {
        $html = parent::fetch($template, $vars, $replace, $config, $renderContent);
        return $this->isMinify ? Minify::getSingleton()->html($html) : $html;
    }

    /**
     * 成功跳转
     *
     * @param string $msg            
     * @param string $url            
     * @param string $data            
     * @param number $wait            
     * @param array $header            
     * @return mixed
     */
    public function success($msg = '', $url = '', $data = '', $wait = 3, $header = [])
    {
        return $this->result(1, $msg, $url, $data, $wait, $header);
    }

    /**
     * 错误跳转
     *
     * @param string $msg            
     * @param string $url            
     * @param string $data            
     * @param number $wait            
     * @param array $header            
     * @return mixed
     */
    public function error($msg = '', $url = '', $data = '', $wait = 3, $header = [])
    {
        return $this->result(0, $msg, $url, $data, $wait, $header);
    }

    /**
     * 跳转链接
     *
     * @param number $code            
     * @param string $msg            
     * @param string $url            
     * @param string $data            
     * @param number $wait            
     * @param array $header            
     * @return mixed
     */
    public function result($code = 1, $msg = '', $url = '', $data = '', $wait = 3, $header = [])
    {
        $response = Response::getSingleton();
        $data = $this->formatResult($code, $msg, $url, $data, $wait);
        if (Request::instance()->isAjax()) {
            $response->json($data);
        } else {
            $template = $this->getTemplate($code);
            $content = $this->fetch($template, $data);
            $response->data($content, '', 200, $header);
        }
    }

    /**
     * 设置渲染模板
     *
     * @param integer $code            
     * @param string $template            
     * @return void
     */
    public function setTemplate($code, $template)
    {
        if ($code == 1) {
            $this->successTemplate = $template;
        } else {
            $this->errorTemplate = $template;
        }
    }

    /**
     * 设置是否压缩
     *
     * @param boolean $minify            
     */
    public function setMinify($minify = false)
    {
        $this->isMinify = $minify;
    }

    /**
     * 获取渲染模板
     *
     * @param integer $code            
     * @return string
     */
    protected function getTemplate($code)
    {
        if ($code == 1) {
            return $this->successTemplate ?: Config::get('dispatch_success_tmpl');
        } else {
            return $this->errorTemplate ?: Config::get('dispatch_error_tmpl');
        }
    }

    /**
     * 格式化结果
     *
     * @param integer $code            
     * @param string $msg            
     * @param string $url            
     * @param string $data            
     * @param number $wait            
     * @return array
     */
    protected function formatResult($code, $msg = '', $url = '', $data = '', $wait = 3)
    {
        return [
            'code' => $code,
            'msg' => $msg,
            'url' => $url,
            'data' => $data,
            'wait' => $wait
        ];
    }
}