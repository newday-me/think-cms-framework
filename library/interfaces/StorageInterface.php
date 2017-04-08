<?php
namespace cms\interfaces;

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
     * 文件信息
     *
     * @param string $path            
     *
     * @return array
     */
    public function info($path);

    /**
     * 文件列表
     *
     * @param string $dir            
     *
     * @return array
     */
    public function lists($dir, $page = 1, $size = 10);

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
     * 读取文件
     *
     * @param string $path            
     *
     * @return string
     */
    public function read($path);

    /**
     * 删除文件
     *
     * @param string $path            
     *
     * @return boolean
     */
    public function delete($path);

    /**
     * 文件链接
     *
     * @param unknown $path            
     *
     * @return string
     */
    public function url($path);

}