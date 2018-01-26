<?php

namespace cms\support;

use think\Loader;
use think\facade\Env;
use think\facade\Request;
use cms\core\traits\InstanceTrait;

class Util
{
    /**
     * 实例Trait
     */
    use InstanceTrait;

    /**
     * 客户端IP
     *
     * @return string
     */
    public function getIp()
    {
        return Request::ip();
    }

    /**
     * 客户端浏览器标识
     *
     * @return string
     */
    public function getAgent()
    {
        return Request::server('HTTP_USER_AGENT');
    }

    /**
     * 当前操作
     *
     * @return string
     */
    public function getCurrentAction()
    {
        return Request::module() . '/' . Loader::parseName(Request::controller()) . '/' . Request::action();
    }

    /**
     * 随机哈希字符串
     *
     * @param int $length
     * @param string $prefix
     * @return string
     */
    public function randHashStr($length = 16, $prefix = '')
    {
        $size = $length - strlen($prefix);
        $offset = intval(32 - $size) / 2;
        return $prefix . substr(md5(microtime(true) . mt_rand(1000, 9999)), $offset, $size);
    }

    /**
     * 临时文件
     *
     * @param string $prefix
     * @return string
     */
    public function tmpFile($prefix = null)
    {
        $tmpPath = Env::get('RUNTIME_PATH') . 'file/';

        // 不存在则创建
        is_dir($tmpPath) || mkdir($tmpPath, 0755, true);

        $prefix || $prefix = 'tmp';
        return tempnam($tmpPath, $prefix);
    }

    /**
     * 读取文件
     *
     * @param string $filePath
     * @return string
     */
    public function readFile($filePath)
    {
        try {
            $file = fopen($filePath, 'r');
            $content = fread($file, filesize($filePath));
            fclose($file);
            return $content;
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * 转换文件大小
     *
     * @param string $size
     *
     * @return integer
     */
    public function translateBytes($size)
    {
        $units = [
            'k' => 1,
            'm' => 2,
            'g' => 3,
            't' => 4,
            'p' => 5
        ];
        $size = strtolower($size);
        $bytes = intval($size);
        foreach ($units as $key => $value) {
            if (strpos($size, $key)) {
                $bytes = $bytes * pow(1024, $value);
                break;
            }
        }
        return $bytes;
    }

    /**
     * 文件大小格式化
     *
     * @param number $size
     * @param string $delimiter
     * @return string
     */
    public function formatBytes($size, $delimiter = '')
    {
        $units = [
            'B',
            'KB',
            'MB',
            'GB',
            'TB',
            'PB'
        ];
        for ($i = 0; $size >= 1024 && $i < 5; $i++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }

}