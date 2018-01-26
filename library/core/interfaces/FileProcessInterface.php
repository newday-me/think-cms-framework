<?php

namespace cms\core\interfaces;

interface FileProcessInterface
{

    /**
     * 处理文件
     *
     * @param FileInterface $file
     *
     * @throws \Exception
     * @return bool
     */
    public function process(FileInterface $file);

}