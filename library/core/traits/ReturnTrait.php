<?php

namespace cms\core\traits;

use cms\core\objects\ReturnObject;
use cms\core\constant\CodeConstant;

trait ReturnTrait
{

    /**
     * 消息映射
     *
     * @var array
     */
    protected $_msgMapping = [
        CodeConstant::CODE_ERROR => 'error',
        CodeConstant::CODE_SUCCESS => 'success'
    ];

    /**
     * 消息映射
     *
     * @return array
     */
    protected function getMsgMapping()
    {
        return [];
    }

    /**
     * 成功返回
     *
     * @param string $msg
     * @param string $data
     * @return ReturnObject
     */
    protected function returnSuccess($msg = '', $data = '')
    {
        return $this->returnData(CodeConstant::CODE_SUCCESS, $msg, $data);
    }

    /**
     * 失败返回
     *
     * @param string $msg
     * @param string $data
     * @return ReturnObject
     */
    protected function returnError($msg = '', $data = '')
    {
        return $this->returnData(CodeConstant::CODE_ERROR, $msg, $data);
    }

    /**
     * 返回数据
     *
     * @param int $code
     * @param string $msg
     * @param string $data
     * @return ReturnObject
     */
    protected function returnData($code, $msg = '', $data = '')
    {
        $object = new ReturnObject();

        // 设置状态码
        $object->setCode($code);

        // 设置消息
        $msgMapping = array_merge($this->_msgMapping, $this->getMsgMapping());
        if (empty($msg) && isset($msgMapping[$code])) {
            $msg = $msgMapping[$code];
        }
        $object->setMsg($msg);

        // 设置数据
        $object->setData($data);

        return $object;
    }
}