<?php
namespace cms\files;

use cms\File;
use cms\interfaces\FileInterface;

class LocalFile extends File
{

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::load()
     */
    public function load($file)
    {
        $this->filePath = $file;
        
        if (! is_file($this->filePath)) {
            $this->logError('文件[' . $file . ']不存在');
            return false;
        }
        
        return true;
    }

}