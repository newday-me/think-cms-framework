<?php

namespace cms\core\interfaces;

interface FileValidateInterface
{

    /**
     * 验证文件
     *
     * @param FileInterface $file
     *
     * @throws \Exception
     * @return bool
     */
    public function validate(FileInterface $file);

}