<?php
namespace cms\files;

class StreamFile extends LocalFile
{

    /**
     * 文件流
     *
     * @var unknown
     */
    protected $stream;

    /**
     *
     * {@inheritdoc}
     *
     * @see LocalFile::load()
     */
    public function load($file)
    {
        $this->stream = $file;
        
        if (empty($this->stream)) {
            $this->logError('文件内容为空');
            return false;
        }
        
        $tempPath = $this->getTempPath();
        if (! is_writable($tempPath)) {
            $this->logError('临时文件夹不可写入');
            return false;
        }
        
        $filePath = $tempPath . '/' . $this->getUniqueName();
        file_put_contents($filePath, $this->stream);
        
        return parent::load($filePath);
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
        return $tempPath ? $tempPath : sys_get_temp_dir();
    }
}