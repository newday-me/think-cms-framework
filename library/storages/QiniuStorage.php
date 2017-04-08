<?php
namespace cms\storages;

use cms\Storage;
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;
use cms\interfaces\FileInterface;

class QiniuStorage extends Storage
{

    /**
     * 授权
     *
     * @var unknown
     */
    protected $_auth;

    /**
     * 七牛云对象
     *
     * @var unknown
     */
    protected $_qiniu;

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::exists()
     */
    public function exists($path)
    {
        parent::exists($path);
        
        return $this->info($path) ? true : false;
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
        
        $key = $this->parsePathPrefix($path);
        list ($ret, $error) = $this->qiniu()->stat($this->getBucket(), $key);
        if ($ret) {
            $name = basename($path);
            $path = $this->parsePath($path);
            $mtime = ceil($ret['putTime'] / 10000000);
            list ($mime) = explode(';', $ret['mimeType']);
            $extra = [
                'mime' => $mime
            ];
            return $this->formatInfo($name, $path, static::TYPE_FILE, $ret['fsize'], $mtime, $extra);
        } else {
            $list = $this->lists($key);
            if (count($list)) {
                $name = basename($key);
                $path = $this->parsePath($path);
                $mtime = $list[0]['mtime'];
                return $this->formatInfo($name, $path, static::TYPE_DIRECTORY, 0, $mtime);
            }
        }
        return null;
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
        
        $prefix = $this->parsePathPrefix($dir);
        $marker = '';
        $list = [];
        do {
            list ($files, $marker, $err) = $this->qiniu()->listFiles($this->getBucket(), $prefix, $marker, $size);
            foreach ($files as $file) {
                if (empty($file['key'])) {
                    continue;
                }
                
                $path = $this->strReplaceOnce($prefix . static::DS, '', $file['key']);
                if (strpos($path, static::DS) !== false) {
                    list ($name) = explode(static::DS, $path, 2);
                    $path = $prefix . static::DS . $name;
                    $type = static::TYPE_DIRECTORY;
                    $fsize = 0;
                    $extra = [];
                } else {
                    $name = basename($path);
                    $path = $file['key'];
                    $type = static::TYPE_FILE;
                    $fsize = $file['fsize'];
                    list ($mime) = explode(';', $file['mimeType']);
                    $extra = [
                        'mime' => $mime
                    ];
                }
                $path = $this->parsePath(static::DS . $path);
                $mtime = ceil($file['putTime'] / 10000000);
                $list[] = $this->formatInfo($name, $path, $type, $fsize, $mtime, $extra);
            }
        } while (! empty($marker));
        
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
        
        $token = $this->auth()->uploadToken($this->getBucket());
        $manager = new UploadManager();
        $key = $this->parsePathPrefix($path);
        list ($ret, $error) = $manager->putFile($token, $key, $file->getPath());
        return $error ? false : true;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::read()
     */
    public function read($path)
    {
        parent::read($path);
        
        $url = $this->getBaseUrl() . $path;
        $url = $this->auth()->privateDownloadUrl($url);
        return $this->fetchUrl($url);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::delete()
     */
    public function delete($path)
    {
        parent::delete($path);
        
        $key = $this->parsePathPrefix($path);
        return $this->qiniu()->delete($this->getBucket(), $key) ? false : true;
    }

    /**
     * 授权对象
     *
     * @return Auth
     */
    public function auth()
    {
        if (is_null($this->_auth)) {
            $akey = $this->getOption('akey');
            $skey = $this->getOption('skey');
            $this->_auth = new Auth($akey, $skey);
        }
        return $this->_auth;
    }

    /**
     * 七牛云对象
     *
     * @return BucketManager
     */
    public function qiniu()
    {
        if (is_null($this->_qiniu)) {
            $this->_qiniu = new BucketManager($this->auth());
        }
        return $this->_qiniu;
    }

    /**
     * Bucket名称
     *
     * @return string
     */
    public function getBucket()
    {
        return $this->getOption('bucket');
    }

    /**
     * 获取基于链接
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->getOption('base_url');
    }

    /**
     * 抓取远程文件
     *
     * @param unknown $url            
     *
     * @return string|null
     */
    protected function fetchUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        
        // HTTPS
        if (strpos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        
        $res = curl_exec($ch);
        curl_close($ch);
        
        return $res;
    }

    /**
     * 字符串替换一次
     *
     * @param string $needle            
     * @param string $replace            
     * @param string $haystack            
     * @return string
     */
    protected function strReplaceOnce($needle, $replace, $haystack)
    {
        $pos = strpos($haystack, $needle);
        if ($pos === false) {
            return $haystack;
        }
        return substr_replace($haystack, $replace, $pos, strlen($needle));
    }

    /**
     *
     * 获取路径前缀
     *
     * @param unknown $path            
     */
    protected function parsePathPrefix($path)
    {
        $path = $this->parsePath($path);
        
        if (substr($path, 0, 1) == '/') {
            $path = substr($path, 1);
        }
        
        if (substr($path, - 1) == '/') {
            $path = substr($path, 0, - 1);
        }
        
        return $path;
    }
}