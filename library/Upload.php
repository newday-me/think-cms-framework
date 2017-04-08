<?php
namespace cms;

use cms\traits\LogTrait;
use cms\traits\OptionTrait;
use cms\traits\HookTrait;
use cms\interfaces\UploadInterface;
use cms\interfaces\StorageInterface;
use cms\interfaces\FileInterface;
use cms\interfaces\FileProcessInterface;
use cms\interfaces\FileValidateInterface;

class Upload implements UploadInterface
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
     * 钩子Trait
     */
    use HookTrait;

    /**
     * 上传验证钩子
     *
     * @var unknown
     */
    const HOOK_UPLOAD_CHECK = 'upload_check';

    /**
     * 上传成功钩子
     *
     * @var unknown
     */
    const HOOK_UPLOAD_SUCCESS = 'upload_success';

    /**
     * 文件存储
     *
     * @var StorageInterface
     */
    protected $_storage;

    /**
     * 文件验证
     *
     * @var unknown
     */
    protected $_validates;

    /**
     * 文件处理
     *
     * @var unknown
     */
    protected $_processors;

    /**
     * 路径格式
     *
     * @var unknown
     */
    protected $_pathFormat = '{ext}/{hash}.{ext}';

    /**
     * 构造函数
     *
     * @param StorageInterface $storage            
     * @return void
     */
    public function __construct(StorageInterface $storage = null)
    {
        is_null($storage) || $this->_storage = $storage;
        
        // 重设上传
        $this->resetUpload();
    }

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
            $this->logError('未设置存储对象');
            throw new \Exception('请先设置存储对象');
        }
        
        // 处理
        foreach ($this->_processors as $processor) {
            $processor->process($file);
        }
        
        // 验证
        foreach ($this->_validates as $validate) {
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
            $path = $storage->getOption('dir') . str_replace(array_keys($vars), array_values($vars), $this->_pathFormat);
        }
        $info['path'] = $path;
        
        // 上传验证钩子
        $this->callHook(self::HOOK_UPLOAD_CHECK, $info);
        
        // 保存文件
        if (empty($info['url']) || $overwrite) {
            $storage->exists($path) || $storage->save($file, $storage->getOption('root') . $path);
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
     *
     * @return string
     */
    public function setPathFormat($format)
    {
        $this->_pathFormat = $format;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see UploadInterface::addValidate()
     */
    public function addValidate(FileValidateInterface $validate)
    {
        $this->_validates[] = $validate;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see UploadInterface::addProcesser()
     */
    public function addProcesser(FileProcessInterface $processor)
    {
        $this->_processors[] = $processor;
    }

    /**
     * 设置存储对象
     *
     * @param StorageInterface $storage            
     * @return void
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->_storage = $storage;
    }

    /**
     * 获取存储对象
     *
     * @return StorageInterface
     */
    public function getStorage()
    {
        return $this->_storage;
    }

    /**
     * 重设上传
     *
     * @return void
     */
    public function resetUpload()
    {
        $this->resetValidate();
        $this->resetProcesser();
    }

    /**
     * 重设文件验证
     *
     * @return void
     */
    protected function resetValidate()
    {
        $this->_validates = [];
    }

    /**
     * 重设文件处理
     *
     * @return void
     */
    protected function resetProcesser()
    {
        $this->_processors = [];
    }
}