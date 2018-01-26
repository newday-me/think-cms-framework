<?php

namespace cms\upload\validate;

use cms\support\Util;
use cms\upload\Validate;
use cms\core\interfaces\FileInterface;
use cms\core\interfaces\FileValidateInterface;
use cms\core\exception\FileValidateException;

class SizeValidate extends Validate
{

    /**
     *
     * {@inheritdoc}
     *
     * @see FileValidateInterface::validate()
     */
    public function validate(FileInterface $file)
    {
        $util = Util::getSingleton();

        // 最小值判断
        $minSize = $util->translateBytes($this->getOption('min_size'));
        if ($minSize && $file->getSize() < $minSize) {
            throw new FileValidateException('文件小于允许文件上传的最小值[' . $util->formatBytes($minSize) . ']');
        }

        // 最大值判断
        $maxSize = $util->translateBytes($this->getOption('max_size'));
        if ($maxSize && $file->getSize() > $maxSize) {
            throw new FileValidateException('文件超过允许文件上传的最大值[' . $util->formatBytes($maxSize) . ']');
        }

        return true;
    }

}