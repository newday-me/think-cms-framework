<?php

namespace cms\upload;

use cms\core\traits\OptionTrait;
use cms\core\interfaces\StorageInterface;

abstract class Storage implements StorageInterface
{
    /**
     * 配置Trait
     */
    use OptionTrait;

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::url()
     */
    public function url($path)
    {
        return $this->getOption('base_url') . $path;
    }
}