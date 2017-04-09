<?php
namespace cms\exception;

use Exception;
use think\Config;
use cms\View;
use think\exception\HttpResponseException;

class Handle extends \think\exception\Handle
{

    /**
     *
     * {@inheritdoc}
     *
     * @see Handle::convertExceptionToResponse()
     */
    public function convertExceptionToResponse(Exception $e)
    {
        if (! Config::get('app_debug')) {
            
            // 清空输出缓存
            ob_clean();
            
            try {
                $this->getView()->error($e->getMessage());
            } catch (Exception $e) {
                if ($e instanceof HttpResponseException) {
                    return $e->getResponse();
                }
            }
        }
        return parent::convertExceptionToResponse($e);
    }

    /**
     * 获取渲染视图
     *
     * @return View
     */
    protected function getView()
    {
        return new View();
    }
}