<?php

namespace cms\core\interfaces;

interface StorageInterface
{

    /**
     * 文件是否存在
     *
     * @param string $path
     *
     * @return boolean
     */
    public function exists($path);

    /**
     * 文件链接
     *
     * @param string $path
     *
     * @return string
     */
    public function url($path);

    /**
     * 读取文件
     *
     * @param string $path
     *
     * @return string
     */
    public function read($path);

    /**
     * 保存文件
     *
     * @param FileInterface $file
     * @param string $path
     *
     * @return boolean
     */
    public function save(FileInterface $file, $path);

    /**
     * 删除文件
     *
     * @param string $path
     *
     * @return boolean
     */
    public function delete($path);

}