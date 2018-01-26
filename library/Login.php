<?php

namespace cms;

use cms\traits\OptionTrait;
use cms\traits\InstanceTrait;
use cms\interfaces\LoginInterface;

abstract class Login implements LoginInterface
{

    /**
     * 实例Trait
     */
    use InstanceTrait;

    /**
     * 配置Trait
     */
    use OptionTrait;

    /**
     * 前缀
     *
     * @var string
     */
    protected $prefix = 'login_';

    /**
     * 设置前缀
     *
     * @param string $prefix
     */
    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * 获取存储key
     *
     *
     * @param string $key
     * @return string
     */
    protected function getStorageKey($key)
    {
        return $this->prefix . $key;
    }

}