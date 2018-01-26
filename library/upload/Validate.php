<?php

namespace cms\upload;

use cms\core\traits\OptionTrait;
use cms\core\interfaces\FileValidateInterface;

abstract class Validate implements FileValidateInterface
{
    /**
     * 配置Trait
     */
    use OptionTrait;

    /**
     * 构造函数
     *
     * @param array $option
     */
    public function __construct($option = [])
    {
        $this->setOption($option);
    }
}