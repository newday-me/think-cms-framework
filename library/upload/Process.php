<?php
namespace cms\upload;

use cms\traits\OptionTrait;
use cms\interfaces\FileProcessInterface;

abstract class Process implements FileProcessInterface
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