<?php

namespace cms\upload;

use Mimey\MimeTypes;
use Mimey\MimeTypesInterface;
use cms\core\traits\OptionTrait;
use cms\core\interfaces\FileInterface;

abstract class File implements FileInterface
{
    /**
     * 配置Trait
     */
    use OptionTrait;

    /**
     * 文件路径
     *
     * @var string
     */
    protected $filePath;

    /**
     * mime解析器
     *
     * @var MimeTypesInterface
     */
    protected $mimeParser;

    /**
     * 构造文件
     *
     * @param string $file
     * @return static|null
     */
    public static function from($file)
    {
        $instance = new static();
        return $instance->load($file) ? $instance : null;
    }

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
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $fileMime = finfo_file($fileInfo, $this->getPath());
        finfo_close($fileInfo);
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