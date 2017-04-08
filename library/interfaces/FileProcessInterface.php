<?php
namespace cms\interfaces;

interface FileProcessInterface
{

    /**
     * 处理文件
     *
     * @param FileInterface $file            
     *
     * @throws \Exception
     * @return void
     */
    public function process(FileInterface $file);

}