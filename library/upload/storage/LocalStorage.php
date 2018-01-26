<?php

namespace cms\upload\storage;

use cms\upload\Storage;
use cms\core\interfaces\FileInterface;

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
        return file_exists($path);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::read($path)
     */
    public function read($path)
    {
        if ($this->exists($path)) {
            return file_get_contents($path);
        } else {
            return null;
        }
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::save()
     */
    public function save(FileInterface $file, $path)
    {
        $path = $this->getOption('base_path') . $path;

        // 创建文件夹
        $dir = dirname($path);
        is_dir($dir) || @mkdir($dir, 0755, true);

        return rename($file->getPath(), $path);
    }


    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::delete($path)
     */
    public function delete($path)
    {
        $path = $this->getOption('base_path') . $path;
        if ($this->exists($path)) {
            return unlink($path);
        } else {
            return false;
        }
    }

}