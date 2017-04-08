<?php
namespace cms\interfaces;

interface FileValidateInterface
{

    /**
     * 验证文件
     *
     * @param FileInterface $file            
     *
     * @throws \Exception
     * @return void
     */
    public function validate(FileInterface $file);

}