<?php

namespace cms;

use cms\traits\OptionTrait;

class Crypt
{

    /**
     * 配置Trait
     */
    use OptionTrait;

    /**
     * 密码学方式 openssl_get_cipher_methods()
     *
     * @var string
     */
    private $method;

    /**
     * 密钥
     *
     * @var string
     */
    private $key;

    /**
     * 向量
     *
     * @var string
     */
    private $iv;

    /**
     * base64编码
     *
     * @var string
     */
    const CODE_BASE64 = 'base64';

    /**
     * 十六进制编码
     *
     * @var string
     */
    const CODE_HEX = 'hex';

    /**
     * 二进制编码
     *
     * @var string
     */
    const CODE_BIN = 'bin';

    /**
     * url编码
     *
     * @var string
     */
    const CODE_URL = 'url';

    /**
     * 构造函数
     *
     * @param array $option
     */
    public function __construct($option = [])
    {
        $this->init($option);
    }

    /**
     * 初始化
     *
     * @param array $option
     * @throws \Exception
     */
    public function init($option)
    {
        $this->_option = $option;

        // 密码学方式
        $this->method = $this->getOption('method');
        if (empty($this->method)) {
            throw new \Exception('加密method为空');
        }

        // key
        $this->key = $this->getOption('key');
        if (empty($this->key)) {
            throw new \Exception('加密key为空');
        }

        // 向量
        $this->iv = $this->getOption('iv');

        // 检验向量长度
        $ivLength = openssl_cipher_iv_length($this->method);
        if (strlen($this->iv) != $ivLength) {
            throw new \Exception('[' . $this->method . ']的iv长度应该为' . $ivLength);
        }
    }

    /**
     * 加密
     *
     * @param string $str
     * @param string $code
     *
     * @return string
     */
    public function encrypt($str, $code = self::CODE_URL)
    {
        $result = openssl_encrypt($str, $this->method, $this->key, 0, $this->iv);

        switch ($code) {
            case self::CODE_BASE64:
                $ret = base64_encode($result);
                break;
            case self::CODE_HEX:
                $ret = bin2hex($result);
                break;
            case self::CODE_BIN:
                $ret = $result;
                break;
            case self::CODE_URL:
            default:
                $ret = $this->urlEncode($result);
                break;
        }

        return $ret;
    }

    /**
     * 解密
     *
     * @param string $str
     * @param string $code
     *
     * @return string
     */
    public function decrypt($str, $code = self::CODE_URL)
    {
        switch ($code) {
            case self::CODE_BASE64:
                $str = base64_decode($str);
                break;
            case self::CODE_HEX:
                $str = $this->hex2bin($str);
                break;
            case self::CODE_URL:
                $str = $this->urlDecode($str);
                break;
        }
        $ret = openssl_decrypt($str, $this->method, $this->key, 0, $this->iv);

        return $ret;
    }

    /**
     * url编码
     *
     * @param string $str
     *
     * @return mixed
     */
    private function urlEncode($str)
    {
        $str = base64_encode($str);
        return str_replace([
            '+',
            '/',
            '='
        ], [
            '_',
            '-',
            ''
        ], $str);
    }

    /**
     * url解码
     *
     * @param string $str
     *
     * @return string
     */
    private function urlDecode($str)
    {
        $str = str_replace([
            '_',
            '-'
        ], [
            '+',
            '/'
        ], $str);
        return base64_decode($str);
    }

    /**
     * 十六进制转二进制
     *
     * @param bool $hex
     *
     * @return string
     */
    private function hex2bin($hex = false)
    {
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
        return $ret;
    }
}