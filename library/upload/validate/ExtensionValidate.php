<?php

namespace cms\upload\validate;

use cms\upload\Validate;
use cms\core\interfaces\FileInterface;
use cms\core\interfaces\FileValidateInterface;
use cms\core\exception\FileValidateException;

class ExtensionValidate extends Validate
{

    /**
     *
     * {@inheritdoc}
     *
     * @see FileValidateInterface::validate()
     */
    public function validate(FileInterface $file)
    {
        // 无配置则不限
        $extensions = $this->getOption('extensions');
        if (empty($extensions)) {
            return true;
        }

        // 后缀判断
        if (!in_array($file->getExtension(), $extensions)) {
            throw new FileValidateException('不允许上传后缀为[' . $file->getExtension() . ']的文件');
        }

        return true;
    }

}