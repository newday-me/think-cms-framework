<?php
namespace cms\storages;

use cms\Storage;
use FtpClient\FtpClient;
use cms\interfaces\FileInterface;

class FtpStorage extends Storage
{

    /**
     * FTP连接
     *
     * @var unknown
     */
    protected $_connect;

    /**
     * 默认端口
     *
     * @var unknown
     */
    const DEFAULT_PORT = 21;

    /**
     * 默认超时
     *
     * @var unknown
     */
    const DEFAULT_TIMEOUT = 60;

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::exists()
     */
    public function exists($path)
    {
        parent::exists($path);
        
        $info = $this->info($path);
        return $info ? true : false;
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
        
        $dir = $this->parsePath(dirname($path));
        $name = basename($path);
        $list = $this->lists($dir, 0);
        
        foreach ($list as $vo) {
            if ($vo['name'] == $name) {
                return $vo;
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
        
        // 文件夹不存在
        if (! $this->connect()->isDir($dir)) {
            return [];
        }
        
        $files = $this->connect()->scanDir($dir);
        $list = [];
        foreach ($files as $key => $file) {
            
            list ($type, $path) = explode('#', $key, 2);
            $path = $this->parsePath($path);
            switch ($type) {
                case 'directory':
                    $type = static::TYPE_DIRECTORY;
                    break;
                case 'link':
                    $type = static::TYPE_LINK;
                    break;
                default:
                    $type = static::TYPE_FILE;
            }
            
            if (strpos($file['time'], ':')) {
                $mtimeStr = date('Y') . '-' . $file['month'] . '-' . $file['day'] . ' ' . $file['time'];
            } else {
                $mtimeStr = $file['time'] . '-' . $file['month'] . '-' . $file['day'];
            }
            $mtime = strtotime($mtimeStr);
            $extra = [
                'permissions' => $file['permissions'],
                'user' => $file['owner'],
                'group' => $file['group']
            ];
            $list[] = $this->formatInfo($file['name'], $path, $type, $file['size'], $mtime, $extra);
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
        $this->connect()->isDir($dir) || $this->connect()->mkdir($dir, true);
        
        return $this->connect()->nb_put($path, $file->getPath(), FTP_BINARY);
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
        
        // 保存为临时流
        $handle = fopen('php://temp', 'w');
        $this->connect()->nb_fget($handle, $path, FTP_BINARY);
        rewind($handle);
        
        // 读取临时流
        $content = '';
        while (! feof($handle)) {
            $content .= fgets($handle);
        }
        fclose($handle);
        
        return $content;
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
        
        return $this->connect()->delete($path);
    }

    /**
     * 返回FTP连接
     *
     * @param boolean $new            
     * @return FtpClient
     */
    public function connect($new = false)
    {
        if (is_null($this->_connect) || $new) {
            $host = $this->getOption('host');
            $ssl = $this->getOption('ssl') ? true : false;
            $port = $this->getOption('port') ? $this->getOption('port') : static::DEFAULT_PORT;
            $timeout = $this->getOption('timeout') ? $this->getOption('timeout') : static::DEFAULT_TIMEOUT;
            $user = $this->getOption('user') ? $this->getOption('user') : 'anonymous';
            $passwd = $this->getOption('passwd') ? $this->getOption('passwd') : '';
            
            $this->logDebug(sprintf('连接FTP服务器,Host:%s,Port:%d,User:%s', $host, $port, $user));
            
            $connect = new FtpClient();
            $connect->connect($host, $ssl, $port, $timeout);
            $connect->login($user, $passwd);
            $this->_connect = $connect;
        }
        return $this->_connect;
    }

}