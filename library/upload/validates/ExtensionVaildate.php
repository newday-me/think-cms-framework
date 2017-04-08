<?php
namespace cms\upload\validates;

use cms\upload\Validate;
use cms\interfaces\FileInterface;
use cms\interfaces\FileValidateInterface;

class ExtensionVaildate extends Validate
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
        if (! in_array($file->getExtension(), $extensions)) {
            throw new \Exception('不允许上传后缀为[' . $file->getExtension() . ']的文件');
        }
        
        return true;
    }

}