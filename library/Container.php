<?php
namespace cms;

class Container extends \Pimple\Container
{

    /**
     * 供应商
     *
     * @var array
     */
    protected $providers = [];

    /**
     * 初始化记录
     *
     * @var unknown
     */
    protected $records = [];

    /**
     * 构造函数
     *
     * @param array $values            
     */
    public function __construct(array $values = array())
    {
        parent::__construct($values);
        
        // 注册服务商
        $this->registerProviders();
    }

    /**
     * 增加供应商
     *
     * @param string $provider            
     * @return self
     */
    public function addProvider($provider)
    {
        array_push($this->providers, $provider);
        
        // 注册服务商
        $this->registerProviders();
    }

    /**
     * 设置供应商
     *
     * @param array $providers            
     */
    public function setProviders(array $providers)
    {
        $this->providers = [];
        
        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }
    }

    /**
     * 返回所有供应商
     *
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * 动态获取供应商
     *
     * @param string $id            
     * @return mixed
     */
    public function __get($id)
    {
        return $this->offsetGet($id);
    }

    /**
     * 动态设置供应商
     *
     * @param string $id            
     * @param mixed $value            
     */
    public function __set($id, $value)
    {
        $this->offsetSet($id, $value);
    }

    /**
     * 注册供应商
     */
    private function registerProviders()
    {
        foreach ($this->providers as $provider) {
            // 避免重复注册
            $key = serialize($provider);
            if (! isset($this->records[$key])) {
                $this->register(new $provider());
                $this->records[$key] = true;
            }
        }
    }

}