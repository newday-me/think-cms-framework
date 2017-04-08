<?php
namespace cms\interfaces;

interface LoginInterface
{

    /**
     * 存储登录
     *
     * @param string $key            
     * @param mixed $data            
     * @param number $expire            
     */
    public function storageLogin($key, $data, $expire = 0);

    /**
     * 读取登录
     *
     * @param string $key            
     */
    public function readLogin($key);

    /**
     * 清除登录
     *
     * @param string $key            
     */
    public function clearLogin($key);

}