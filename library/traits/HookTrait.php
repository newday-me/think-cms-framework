<?php
namespace cms\traits;

trait HookTrait
{

    /**
     * 默认钩子方法
     *
     * @var unknown
     */
    protected $_hookMethod = 'hook';

    /**
     * 钩子
     *
     * @var unknown
     */
    protected $_hooks = [];

    /**
     * 获取钩子
     *
     * @param string $hook            
     *
     * @return array|null
     */
    public function getHook($name = null)
    {
        if (empty($name)) {
            return $this->_hooks;
        } else {
            return isset($this->_hooks[$name]) ? $this->_hooks[$name] : [];
        }
    }

    /**
     * 增加钩子
     *
     * @param string $name            
     * @param mixed $behavior            
     * @param integer $weight            
     * @param string $tagKey            
     */
    public function addHook($name, $behavior, $weight = 0, $key = null)
    {
        if (is_array($behavior)) {
            // 添加多个钩子
            foreach ($behavior as $item) {
                $this->addHook($name, $item, $key, $weight);
            }
        } else {
            isset($this->_hooks[$name]) || $this->_hooks[$name] = [];
            
            // 避免重复添加
            $key || $key = md5(serialize($behavior));
            $this->_hooks[$name][$key] = $this->formatHook($behavior, $weight, $key);
        }
    }

    /**
     * 调用钩子
     *
     * @param string $name            
     * @param mixed $params            
     * @param mixed $extra            
     *
     * @return array
     */
    public function callHook($name, &$params, $extra = null)
    {
        $results = [];
        $hooks = $this->getHook($name);
        
        // 排序
        usort($hooks, [
            $this,
            'compareHookWeight'
        ]);
        
        foreach ($hooks as $key => $hook) {
            $results[$key] = $this->executeHook($hook['behavior'], $params, $extra);
            
            // 返回false则停止执行
            if (false === $results[$key]) {
                break;
            }
        }
        return $results;
    }

    /**
     * 重置钩子
     */
    public function resetHook()
    {
        $this->_hooks = [];
    }

    /**
     * 执行钩子
     *
     * @param string $behavior            
     * @param mixed $params            
     * @param mixed $extra            
     *
     * @return mixed
     */
    protected function executeHook($behavior, &$params = null, $extra = null)
    {
        if (is_string($behavior)) {
            if (strpos($behavior, '::')) {
                $behavior = explode('::', $behavior, 2);
            } else {
                $behavior = [
                    $behavior,
                    $this->_hookMethod
                ];
            }
        }
        return call_user_func_array($behavior, [
            &$params,
            $extra,
            $this
        ]);
    }

    /**
     * 比较两个钩子权值
     *
     * @param array $a            
     * @param array $b            
     *
     * @return boolean
     */
    protected function compareHookWeight($a, $b)
    {
        return $a['weight'] <= $b['weight'];
    }

    /**
     * 格式化钩子
     *
     * @param mixed $behavior            
     * @param number $weight            
     * @param string $key            
     *
     * @return array
     */
    protected function formatHook($behavior, $weight = 0, $key = null)
    {
        return [
            'behavior' => $behavior,
            'weight' => $weight,
            'key' => $key
        ];
    }

}