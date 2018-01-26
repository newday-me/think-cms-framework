<?php

namespace cms;

use cms\core\traits\OptionTrait;
use cms\core\traits\HookTrait;
use cms\core\interfaces\FileInterface;
use cms\core\interfaces\FileProcessInterface;
use cms\core\interfaces\FileValidateInterface;
use cms\core\interfaces\StorageInterface;
use cms\core\interfaces\UploadInterface;
use cms\core\exception\UploadException;

class Upload implements UploadInterface
{

    /**
     * 配置Trait
     */
    use OptionTrait;

    /**
     * 钩子Trait
     */
    use HookTrait;

    /**
     * 上传验证钩子
     *
     * @var string
     */
    const HOOK_UPLOAD_CHECK = 'upload_check';

    /**
     * 上传成功钩子
     *
     * @var string
     */
    const HOOK_UPLOAD_SUCCESS = 'upload_success';

    /**
     * 文件存储
     *
     * @var StorageInterface
     */
    protected $storage;

    /**
     * 文件验证
     *
     * @var array
     */
    protected $validates = [];

    /**
     * 文件处理
     *
     * @var array
     */
    protected $processes = [];

    /**
     * 路径格式
     *
     * @var string
     */
    protected $pathFormat = '{ext}/{hash}.{ext}';

    /**
     *
     * {@inheritdoc}
     *
     * @see UploadInterface::upload()
     */
    public function upload(FileInterface $file, $path = null, $overwrite = false)
    {
        // 存储
        $storage = $this->getStorage();
        if (is_null($storage)) {
            throw new UploadException('请先设置存储对象');
        }

        // 处理
        foreach ($this->processes as $processor) {
            $processor->process($file);
        }

        // 验证
        foreach ($this->validates as $validate) {
            $validate->validate($file);
        }

        // 文件信息
        $info = [
            'url' => '',
            'path' => '',
            'name' => $file->getName(),
            'size' => $file->getSize(),
            'hash' => md5_file($file->getPath()),
            'mime' => $file->getMime(),
            'ext' => $file->getExtension()
        ];

        // 保存路径
        if (is_null($path)) {
            $vars = [
                '{Y}' => date('Y'),
                '{m}' => date('m'),
                '{d}' => date('d'),
                '{H}' => date('H'),
                '{i}' => date('i'),
                '{s}' => date('s')
            ];
            foreach ($info as $key => $value) {
                $vars['{' . $key . '}'] = $value;
            }
            $path = $this->getOption('dir') . str_replace(array_keys($vars), array_values($vars), $this->pathFormat);
        }
        $info['path'] = $path;

        // 上传验证钩子
        $this->callHook(self::HOOK_UPLOAD_CHECK, $info);

        // 保存文件
        if (empty($info['url']) || $overwrite) {
            $storage->exists($path) || $storage->save($file, $path);
            $info['url'] = $storage->url($path);
        }

        // 上传成功钩子
        $this->callHook(self::HOOK_UPLOAD_SUCCESS, $info);

        // 重设上传
        $this->resetUpload();

        return $info;
    }

    /**
     * 设置路径格式
     *
     * @param string $format
     */
    public function setPathFormat($format)
    {
        $this->pathFormat = $format;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see UploadInterface::addValidate()
     */
    public function addValidate(FileValidateInterface $validate)
    {
        $this->validates[] = $validate;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see UploadInterface::addProcesser()
     */
    public function addProcess(FileProcessInterface $process)
    {
        $this->processes[] = $process;
    }

    /**
     * 设置存储对象
     *
     * @param StorageInterface $storage
     * @return void
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * 获取存储对象
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * 重设上传
     *
     * @return void
     */
    public function resetUpload()
    {
        $this->resetValidate();
        $this->resetProcess();
    }

    /**
     * 重设文件验证
     *
     * @return void
     */
    protected function resetValidate()
    {
        $this->validates = [];
    }

    /**
     * 重设文件处理
     *
     * @return void
     */
    protected function resetProcess()
    {
        $this->processes = [];
    }
}