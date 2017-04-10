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
     * 算法
     *
     * @var unknown
     */
    private $mcrypt;

    /**
     * 密钥
     *
     * @var unknown
     */
    private $key;

    /**
     * 模式
     *
     * @var unknown
     */
    private $mode;

    /**
     * 向量
     *
     * @var unknown
     */
    private $iv;

    /**
     * 区块大小
     *
     * @var unknown
     */
    private $blockSize;

    /**
     * ofb模式
     *
     * @var string
     */
    const MODE_OFB = MCRYPT_MODE_OFB;

    /**
     * cfb模式
     *
     * @var string
     */
    const MODE_CFB = MCRYPT_MODE_CFB;

    /**
     * ecb模式
     *
     * @var string
     */
    const MODE_ECB = MCRYPT_MODE_ECB;

    /**
     * cbc模式
     *
     * @var string
     */
    const MODE_CBC = MCRYPT_MODE_CBC;

    /**
     * base64编码
     *
     * @var unknown
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
     * @var unknown
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
     *
     * @return void
     */
    public function __construct($option = [])
    {
        $this->init($option);
    }

    /**
     * 初始化
     *
     * @return void
     */
    public function init($option)
    {
        $this->_option = $option;
        
        // 算法
        $this->key = $this->getOption('key');
        switch (strlen($this->key)) {
            case 8:
                $this->mcrypt = MCRYPT_DES;
                break;
            case 16:
                $this->mcrypt = MCRYPT_RIJNDAEL_128;
                break;
            case 32:
                $this->mcrypt = MCRYPT_RIJNDAEL_256;
                break;
            default:
                throw new \Exception('密钥的长度不合法');
        }
        
        // 模式
        $this->mode = strtolower($this->getOption('mode'));
        switch ($this->mode) {
            case self::MODE_OFB:
            case self::MODE_CFB:
            case self::MODE_ECB:
            case self::MODE_CBC:
                break;
            default:
                $this->mode = MCRYPT_MODE_CBC;
        }
        
        // 向量
        $this->iv = $this->getOption('iv');
        
        // 密钥和向量长度验证
        if (strlen($this->key) != strlen($this->iv)) {
            throw new \Exception('密钥和向量的长度不一致');
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
        if ($this->mcrypt == MCRYPT_DES) {
            $str = $this->_pkcs5Pad($str);
        }
        
        if (isset($this->iv)) {
            $result = mcrypt_encrypt($this->mcrypt, $this->key, $str, $this->mode, $this->iv);
        } else {
            @$result = mcrypt_encrypt($this->mcrypt, $this->key, $str, $this->mode);
        }
        
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
        $ret = false;
        
        switch ($code) {
            case self::CODE_BASE64:
                $str = base64_decode($str);
                break;
            case self::CODE_HEX:
                $str = $this->_hex2bin($str);
                break;
            case self::CODE_URL:
                $str = $this->urlDecode($str);
                break;
        }
        
        if ($str !== false) {
            if (isset($this->iv)) {
                $ret = mcrypt_decrypt($this->mcrypt, $this->key, $str, $this->mode, $this->iv);
            } else {
                @$ret = mcrypt_decrypt($this->mcrypt, $this->key, $str, $this->mode);
            }
            if ($this->mcrypt == MCRYPT_DES) {
                $ret = $this->_pkcs5Unpad($ret);
            }
            $ret = trim($ret);
        }
        
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
     * PKCS5 Padding
     *
     * @param string $text            
     *
     * @return string
     */
    private function _pkcs5Pad($text)
    {
        $this->blockSize = mcrypt_get_block_size($this->mcrypt, $this->mode);
        $pad = $this->blockSize - (strlen($text) % $this->blockSize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * PKCS5填充
     *
     * @param string $text            
     *
     * @return string
     */
    private function _pkcs5Unpad($text)
    {
        $pad = ord($text{strlen($text) - 1});
        
        if ($pad > strlen($text)) {
            return false;
        }
        
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        
        $ret = substr($text, 0, - 1 * $pad);
        return $ret;
    }

    /**
     * 十六进制转二进制
     *
     * @param string $hex            
     *
     * @return string
     */
    private function _hex2bin($hex = false)
    {
        $ret = $hex !== false && preg_match('/^[0-9a-fA-F]+$/i', $hex) ? pack("H*", $hex) : false;
        return $ret;
    }
}