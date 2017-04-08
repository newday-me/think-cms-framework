<?php
namespace cms\interfaces;

interface FileInterface
{

    /**
     * 加载文件
     *
     * @param mixed $file            
     *
     * @throws \Exception
     * @return boolean
     */
    public function load($file);

    /**
     * 获取扩展名
     *
     * @return string
     */
    public function getExtension();

    /**
     * 获取文件名
     *
     * @return string
     */
    public function getName();

    /**
     * 获取所在文件夹
     *
     * @return string
     */
    public function getDir();

    /**
     * 获取文件路径
     */
    public function getPath();

    /**
     * 获取文件大小
     *
     * @return integer
     */
    public function getSize();

    /**
     * 获取文件Mime
     *
     * @return string
     */
    public function getMime();

    /**
     * 获取文件内容
     *
     * @return string
     */
    public function getContent();

}