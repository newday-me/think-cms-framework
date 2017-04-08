<?php
namespace cms;

use think\Request;
use think\Loader;
use cms\traits\InstanceTrait;

class Common
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
        return Request::instance()->ip();
    }

    /**
     * 客户端浏览器标识
     *
     * @return string
     */
    public function getAgent()
    {
        return Request::instance()->server('HTTP_USER_AGENT');
    }

    /**
     * 当前操作
     *
     * @return string
     */
    public function getCurrentAction()
    {
        $request = Request::instance();
        return $request->module() . '/' . Loader::parseName($request->controller()) . '/' . $request->action();
    }

    /**
     * 临时文件
     *
     * @param string $prefix            
     * @return string
     */
    public function tmpFile($prefix = null)
    {
        $tmp_path = RUNTIME_PATH . 'file/';
        
        // 不存在则创建
        is_dir($tmp_path) || mkdir($tmp_path, 0755, true);
        
        $prefix || $prefix = 'tmp';
        return tempnam($tmp_path, $prefix);
    }

    /**
     * 读取文件
     *
     * @param string $file_path            
     * @return string
     */
    public function readFile($file_path)
    {
        try {
            $file = fopen($file_path, 'r');
            $content = fread($file, filesize($file_path));
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
        for ($i = 0; $size >= 1024 && $i < 5; $i ++) {
            $size /= 1024;
        }
        return round($size, 2) . $delimiter . $units[$i];
    }

}