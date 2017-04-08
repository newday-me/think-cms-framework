<?php
namespace cms;

use Mimey\MimeTypes;
use Mimey\MimeTypesInterface;
use cms\traits\OptionTrait;
use cms\traits\LogTrait;
use cms\interfaces\FileInterface;

abstract class File implements FileInterface
{
    /**
     * 配置Trait
     */
    use OptionTrait;
    
    /**
     * 日志Trait
     */
    use LogTrait;

    /**
     * 文件路径
     *
     * @var unknown
     */
    protected $filePath;

    /**
     * mime解析器
     *
     * @var unknown
     */
    protected $mimeParser;

    /**
     * 获取Mime解析器
     *
     * @return MimeTypesInterface
     */
    public function getMimeParser()
    {
        if (is_null($this->mimeParser)) {
            $this->mimeParser = new MimeTypes();
        }
        return $this->mimeParser;
    }

    /**
     * 设置Mime解析器
     *
     * @param MimeTypesInterface $mimeParser            
     */
    public function setMimeParser(MimeTypesInterface $mimeParser)
    {
        $this->mimeParser = $mimeParser;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::getExtension()
     */
    public function getExtension()
    {
        return $this->getMimeParser()->getExtension($this->getMime());
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::getName()
     */
    public function getName()
    {
        return basename($this->filePath);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::getDir()
     */
    public function getDir()
    {
        return dirname($this->filePath);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::getPath()
     */
    public function getPath()
    {
        return $this->filePath;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::getSize()
     */
    public function getSize()
    {
        return filesize($this->getPath());
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::getMime()
     */
    public function getMime()
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMime = finfo_file($finfo, $this->getPath());
        finfo_close($finfo);
        return $fileMime;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::getContent()
     */
    public function getContent()
    {
        return file_get_contents($this->getPath());
    }
}