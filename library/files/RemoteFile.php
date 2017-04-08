<?php
namespace cms\files;

class RemoteFile extends StreamFile
{

    /**
     * HTTP对象
     *
     * @var unknown
     */
    protected $http;

    /**
     *
     * {@inheritdoc}
     *
     * @see StreamFile::load()
     */
    public function load($file)
    {
        $stream = $this->fetchUrl($file);
        
        if (empty($stream)) {
            $this->logError('抓取远程文件[' . $file . ']失败');
            return false;
        }
        
        return parent::load($stream);
    }

    /**
     * 抓取远程文件
     *
     * @param string $url            
     *
     * @return string|null
     */
    public function fetchUrl($url)
    {
        $this->logDebug('采集远程文件[' . $url . ']');
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // HTTPS
        if (strpos($url, 'https: // ') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $res = curl_exec($ch);
        curl_close($ch);
        
        return $res;
    }
}