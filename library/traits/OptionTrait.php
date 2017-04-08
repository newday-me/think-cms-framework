<?php
namespace cms\traits;

trait OptionTrait {

    /**
     * 选项
     *
     * @var unknown
     */
    private $_option = [];

    /**
     * 获取选项
     *
     * @param string $name            
     * @param mixed $option            
     * @return mixed
     */
    public function getOption($name = null, $option = null)
    {
        if (is_null($name)) {
            return $this->_option;
        } else {
            $option || $option = $this->getOption();
            if (strpos($name, '.') !== false) {
                list ($key, $name) = explode('.', $name, 2);
                return isset($option[$key]) && is_array($option[$key]) ? $this->getOption($name, $option[$key]) : null;
            } else {
                return isset($option[$name]) ? $option[$name] : null;
            }
        }
    }

    /**
     * 设置选项
     *
     * @param string $name            
     * @param mixed $value            
     */
    public function setOption($name, $value = null)
    {
        if (is_array($name)) {
            $this->_option = $name;
        } elseif (is_null($value)) {
            unset($this->_option[$name]);
        } else {
            $this->_option[$name] = $value;
        }
        return $this;
    }

}