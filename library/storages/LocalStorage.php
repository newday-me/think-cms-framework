<?php
namespace cms\storages;

use cms\Storage;
use cms\interfaces\FileInterface;

class LocalStorage extends Storage
{

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::exists()
     */
    public function exists($path)
    {
        parent::exists($path);
        
        return file_exists($path);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::info()
     */
    public function info($path)
    {
        parent::info($path);
        
        if (! (is_file($path) || is_dir($path))) {
            $this->logError('文件[' . $path . ']不存在');
            return null;
        }
        
        if (is_link($path)) {
            $type = static::TYPE_LINK;
        } elseif (is_dir($path)) {
            $type = static::TYPE_DIRECTORY;
        } else {
            $type = static::TYPE_FILE;
        }
        
        $stat = stat($path);
        return $this->formatInfo(basename($path), $path, $type, $stat['size'], $stat['mtime']);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::lists()
     */
    public function lists($dir, $page = 1, $size = 10)
    {
        parent::lists($dir, $page, $size);
        
        if (! is_dir($dir)) {
            $this->logError('文件夹[' . $dir . ']不存在');
            return null;
        } elseif (! is_readable($dir)) {
            $this->logError('文件夹[' . $dir . ']不可读');
            return null;
        }
        
        $list = [];
        foreach (scandir($dir) as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            
            $path = $dir . static::DS . $file;
            if (is_readable($path)) {
                $info = $this->info($path);
            } else {
                $info = $this->formatInfo(dirname($path), $path);
            }
            $info && $list[] = $info;
        }
        
        return $this->getPage($list, $page, $size);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::save()
     */
    public function save(FileInterface $file, $path)
    {
        parent::save($file, $path);
        
        // 创建文件夹
        $dir = dirname($path);
        is_dir($dir) || mkdir($dir, 0755, true);
        
        return rename($file->getPath(), $path);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::read($path)
     */
    public function read($path)
    {
        parent::read($path);
        
        if (is_file($path)) {
            return file_get_contents($path);
        }
        return null;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::delete($path)
     */
    public function delete($path)
    {
        parent::delete($path);
        
        if (is_file($path)) {
            return unlink($path);
        }
        return false;
    }

}