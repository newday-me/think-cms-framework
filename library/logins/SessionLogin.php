<?php
namespace cms\logins;

use cms\Login;
use think\Session;
use cms\interfaces\LoginInterface;

class SessionLogin extends Login
{

    /**
     * 前缀
     *
     * @var unknown
     */
    protected $prefix = 'login_session_';

    /**
     *
     * {@inheritdoc}
     *
     * @see LoginInterface::storageLogin()
     */
    public function storageLogin($key, $data, $expire = 0)
    {
        Session::set($this->getStorageKey($key), $data);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see LoginInterface::readLogin()
     */
    public function readLogin($key)
    {
        return Session::get($this->getStorageKey($key));
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see LoginInterface::clearLogin()
     */
    public function clearLogin($key)
    {
        Session::delete($this->getStorageKey($key));
    }

}