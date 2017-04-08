<?php
namespace cms\storages;

use cms\Storage;
use Upyun\Upyun;
use Upyun\Config;
use cms\Common;
use cms\interfaces\FileInterface;

class UpyunStorage extends Storage
{

    /**
     * 又拍云对象
     *
     * @var unknown
     */
    protected $_upyun;

    /**
     *
     * {@inheritdoc}
     *
     * @see Storage::exists()
     */
    public function exists($path)
    {
        parent::exists($path);
        
        return $this->upyun()->has($path);
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
        
        $name = basename($path);
        $path = $this->parsePath($path);
        $info = $this->upyun()->info($path);
        switch ($info['x-upyun-file-type']) {
            case 'folder':
                $type = static::TYPE_DIRECTORY;
                $size = 0;
                break;
            default:
                $type = static::TYPE_FILE;
                $size = $info['x-upyun-file-size'];
        }
        return $this->formatInfo($name, $path, $type, $size, $info['x-upyun-file-date']);
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
        
        $start = null;
        $list = [];
        do {
            $files = $this->upyun()->read($dir, null, [
                'X-List-Limit' => $size,
                'X-List-Iter' => $start
            ]);
            $start = $files['iter'];
            
            foreach ($files['files'] as $file) {
                switch ($file['type']) {
                    case 'F':
                        $type = static::TYPE_DIRECTORY;
                        break;
                    default:
                        $type = static::TYPE_FILE;
                }
                $path = $this->parsePath($dir . static::DS . $file['name']);
                $list[] = $this->formatInfo($file['name'], $path, $type, $file['size'], $file['time']);
            }
        } while (! $files['is_end']);
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
        
        $params = [
            'return_url' => $this->getOption('return_url'),
            'notify_url' => $this->getOption('notify_url')
        ];
        return $this->upyun()->write($path, $file->getContent(), $params);
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
        
        return $this->upyun()->read($path);
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
        
        return $this->upyun()->delete($path);
    }

    /**
     * 又拍云上传对象
     *
     * @return Upyun
     */
    protected function upyun()
    {
        if (is_null($this->_upyun)) {
            $bucket = $this->getOption('bucket');
            $user = $this->getOption('user');
            $passwd = $this->getOption('passwd');
            $apiKey = $this->getOption('api_key');
            $maxSize = $this->getOption('max_size') ? $this->getOption('max_size') : '5M';
            $blockSize = $this->getOption('block_size') ? $this->getOption('block_size') : '1M';
            
            $common = Common::getSingleton();
            $config = new Config($bucket, $user, $passwd);
            $config->setFormApiKey($apiKey);
            $config->sizeBoundary = $common->translateBytes($maxSize);
            $config->maxBlockSize = $common->translateBytes($blockSize);
            
            $this->_upyun = new Upyun($config);
        }
        return $this->_upyun;
    }

}