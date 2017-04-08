<?php
namespace cms\upload\validates;

use cms\Common;
use cms\upload\Validate;
use cms\interfaces\FileInterface;
use cms\interfaces\FileValidateInterface;

class SizeVaildate extends Validate
{

    /**
     *
     * {@inheritdoc}
     *
     * @see FileValidateInterface::validate()
     */
    public function validate(FileInterface $file)
    {
        $common = Common::getSingleton();
        
        // 最小值判断
        $minSize = $common->translateBytes($this->getOption('min_size'));
        if ($minSize && $file->getSize() < $minSize) {
            throw new \Exception('文件小于允许文件上传的最小值[' . $common->formatBytes($minSize) . ']');
        }
        
        // 最大值判断
        $maxSize = $common->translateBytes($this->getOption('max_size'));
        if ($maxSize && $file->getSize() > $maxSize) {
            throw new \Exception('文件超过允许文件上传的最大值[' . $common->formatBytes($maxSize) . ']');
        }
        
        return true;
    }

}