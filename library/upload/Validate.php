<?php
namespace cms\upload;

use cms\traits\OptionTrait;
use cms\interfaces\FileValidateInterface;

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
     *
     * @return void
     */
    public function __construct($option = [])
    {
        $this->setOption($option);
    }
}