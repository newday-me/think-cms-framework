<?php
namespace cms\logins;

use cms\Login;
use think\Cache;
use think\Cookie;
use cms\interfaces\LoginInterface;

class CacheLogin extends Login
{

    /**
     * 前缀
     *
     * @var unknown
     */
    protected $prefix = 'login_cache_';

    /**
     *
     * {@inheritdoc}
     *
     * @see LoginInterface::storageLogin()
     */
    public function storageLogin($key, $data, $expire = 0)
    {
        $ticket = md5(time() . rand(1000, 9999) . serialize($data));
        $key = $this->getStorageKey($key);
        
        // 设置Cookie
        Cookie::set($key, $ticket, $expire);
        
        // 缓存数据
        Cache::set($ticket, $data, $expire);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see LoginInterface::readLogin()
     */
    public function readLogin($key)
    {
        $key = $this->getStorageKey($key);
        
        $ticket = Cookie::get($key);
        return $ticket ? Cache::get($ticket) : null;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see LoginInterface::clearLogin()
     */
    public function clearLogin($key)
    {
        $key = $this->getStorageKey($key);
        
        // 清除Cookie
        Cookie::delete($key);
        
        // 清除Cache
        $ticket = Cookie::get($key);
        $ticket && Cache::rm($ticket);
    }

}