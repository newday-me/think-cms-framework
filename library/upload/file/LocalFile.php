<?php

namespace cms\upload\file;

use cms\upload\File;
use cms\core\interfaces\FileInterface;
use cms\core\exception\FileException;

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

        if (!is_file($this->filePath)) {
            throw new FileException('文件[' . $file . ']不存在');
        }

        return true;
    }

}