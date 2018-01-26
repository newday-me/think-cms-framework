<?php

namespace cms\core\objects;

use cms\core\constant\CodeConstant;

class ReturnObject
{
    /**
     *  状态码
     *
     * @var int
     */
    protected $code = CodeConstant::CODE_SUCCESS;

    /**
     * 提示
     *
     * @var string
     */
    protected $msg = '';

    /**
     * 数据
     *
     * @var mixed
     */
    protected $data = '';

    /**
     * 是否成功
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getCode() == CodeConstant::CODE_SUCCESS;
    }

    /**
     * 获取状态码
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * 设置状态码
     *
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * 获取提示
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * 设置提示
     *
     * @param string $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    /**
     * 获取数据
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置数据
     *
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * 获取回复
     *
     * @return array
     */
    public function getResponse()
    {
        return [
            'code' => $this->getCode(),
            'msg' => $this->getMsg(),
            'data' => $this->getData()
        ];
    }
}