<?php
namespace cms;

use cms\traits\LogTrait;
use cms\traits\OptionTrait;
use cms\interfaces\FileInterface;
use cms\interfaces\StorageInterface;

abstract class Storage implements StorageInterface
{

    /**
     * 文件夹分隔符
     *
     * @var unknown
     */
    const DS = '/';

    /**
     * 文件
     *
     * @var unknown
     */
    const TYPE_FILE = 'file';

    /**
     * 文件夹
     *
     * @var unknown
     */
    const TYPE_DIRECTORY = 'directory';

    /**
     * 链接
     *
     * @var unknown
     */
    const TYPE_LINK = 'link';
    
    /**
     * 配置Trait
     */
    use OptionTrait;
    
    /**
     * 日志Trait
     */
    use LogTrait;

    /**
     *
     * {@inheritdoc}
     *
     * @see StorageInterface::exists()
     */
    public function exists($path)
    {
        $this->logDebug('检查[' . $path . ']是否存在');
        return false;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see StorageInterface::info()
     */
    public function info($path)
    {
        $this->logDebug('读取[' . $path . ']的信息');
        return null;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see StorageInterface::lists()
     */
    public function lists($dir, $page = 1, $size = 10)
    {
        $this->logDebug('遍历文件夹[' . $dir . ']');
        return null;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see StorageInterface::save()
     */
    public function save(FileInterface $file, $path)
    {
        $this->logDebug('传输文件[' . $file->getPath() . ']到[' . $path . ']');
        return true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see StorageInterface::read()
     */
    public function read($path)
    {
        $this->logDebug('读取文件[' . $path . ']的内容');
        return '';
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see StorageInterface::delete()
     */
    public function delete($path)
    {
        $this->logDebug('删除文件[' . $path . ']');
        return false;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see StorageInterface::url()
     */
    public function url($path)
    {
        return $this->getOption('base_url') . $path;
    }

    /**
     * 获取分页记录
     *
     * @param array $list            
     * @param number $page            
     * @param number $size            
     *
     * @return array
     */
    protected function getPage($list, $page = 1, $size = 10)
    {
        if ($page == 0) {
            return $list;
        } else {
            $start = ($page - 1) * $size;
            return array_slice($list, $start, $size);
        }
    }

    /**
     * 解析路径
     *
     * @param unknown $path            
     */
    protected function parsePath($path)
    {
        return str_replace([
            '\\',
            '//'
        ], '/', $path);
    }

    /**
     * 格式化文件信息
     *
     * @param string $name            
     * @param string $path            
     * @param string $type            
     * @param number $size            
     * @param number $mtime            
     * @param array $extra            
     *
     * @return array
     */
    protected function formatInfo($name, $path, $type = self::TYPE_FILE, $size = 0, $mtime = 0, $extra = [])
    {
        return [
            'name' => $name,
            'path' => $path,
            'type' => $type,
            'size' => $size,
            'mtime' => $mtime,
            'extra' => $extra
        ];
    }
}