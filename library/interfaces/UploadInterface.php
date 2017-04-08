<?php
namespace cms\interfaces;

interface UploadInterface
{

    /**
     * 上传文件
     *
     * @param FileInterface $file            
     * @param string $path            
     * @param boolean $overwrite            
     *
     * @throws \Exception
     * @return array($url, $info)
     */
    public function upload(FileInterface $file, $path = null, $overwrite = false);

    /**
     * 添加文件验证
     *
     * @param FileValidateInterface $validate            
     *
     * @return void
     */
    public function addValidate(FileValidateInterface $validate);

    /**
     * 添加文件处理
     *
     * @param FileProcessInterface $processor            
     *
     * @return void
     */
    public function addProcesser(FileProcessInterface $processor);

}