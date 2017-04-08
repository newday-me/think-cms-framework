<?php
namespace cms;

use think\Url;
use think\Request;
use think\Config;
use cms\View;

class Controller
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->_initialize();
    }

    /**
     * 初始化
     */
    protected function _initialize()
    {}

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
        // 构造Url
        $url = $this->buildUrl($url);
        
        return $this->getView()->result($code, $msg, $url, $data, $wait, $header);
    }

    /**
     * 模板赋值
     *
     * @param string $name            
     * @param mixed $value            
     */
    protected function assign($name, $value)
    {
        $this->getView()->assign($name, $value);
    }

    /**
     * 初始化模板引擎
     *
     * @param array|string $engine            
     * @return void
     */
    protected function engine($engine)
    {
        $this->getView()->engine($engine);
    }

    /**
     * 视图渲染前
     */
    protected function beforeViewRender()
    {}

    /**
     * 渲染内容输出
     *
     * @param string $content            
     * @param array $vars            
     * @param array $replace            
     * @param array $config            
     * @return mixed
     */
    protected function display($content = '', $vars = [], $replace = [], $config = [])
    {
        $this->beforeViewRender();
        return $this->getView()->display($content, $vars, $replace, $config);
    }

    /**
     * 渲染模板
     *
     * @param string $template            
     * @param array $vars            
     * @param array $replace            
     * @param array $config            
     * @return string
     */
    protected function fetch($template = '', $vars = [], $replace = [], $config = [])
    {
        $this->beforeViewRender();
        return $this->getView()->fetch($template, $vars, $replace, $config);
    }

    /**
     * 是否是ajax请求
     *
     * @return boolean
     */
    protected function isAjaxRequest()
    {
        return $this->getRequest()->isAjax();
    }

    /**
     * 获取请求
     *
     * @return Request
     */
    protected function getRequest()
    {
        return Request::instance();
    }

    /**
     * 获取渲染视图
     *
     * @return View
     */
    protected function getView()
    {
        static $view = null;
        if (is_null($view)) {
            $view = new View();
        }
        return $view;
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