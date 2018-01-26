<?php

namespace cms\upload\file;

use think\facade\Env;
use cms\core\exception\FileException;

class StreamFile extends LocalFile
{

    /**
     * 临时文件
     *
     * @var string
     */
    protected $tempFile;

    /**
     *
     * {@inheritdoc}
     *
     * @see LocalFile::load()
     */
    public function load($file)
    {
        if (empty($file)) {
            throw new FileException('文件内容为空');
        }

        $tempPath = $this->getTempPath();
        is_dir($tempPath) || @mkdir($tempPath, 0755, true);
        if (!is_writable($tempPath)) {
            throw new FileException('临时文件夹不可写入');
        }

        $this->tempFile = $tempPath . '/' . $this->getUniqueName();
        file_put_contents($this->tempFile, $file);

        return parent::load($this->tempFile);
    }

    /**
     * 获取唯一文件名
     *
     * @return string
     */
    public function getUniqueName()
    {
        return microtime(true) . '_' . uniqid();
    }

    /**
     * 获取临时文件夹
     *
     * @return string
     */
    public function getTempPath()
    {
        $tempPath = $this->getOption('temp_path');
        return $tempPath ? $tempPath : Env::get('RUNTIME_PATH') . '/file';
    }

    /**
     * 删除临时文件
     */
    public function __destruct()
    {
        @unlink($this->tempFile);
    }
}