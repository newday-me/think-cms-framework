<?php
namespace cms;

use cms\traits\InstanceTrait;

class Auth
{
    
    /**
     * 实例Trait
     */
    use InstanceTrait;

    /**
     * 是否公共操作
     *
     * @param array $public_action            
     * @return boolean
     */
    public function isPublicAction($public_action = [])
    {
        // 当前操作
        $currentAction = strtolower(Common::getSingleton()->getCurrentAction());
        
        // 匹配规则
        $actionPatern = '#(^' . implode(')|(^', $public_action) . ')#i';
        
        return preg_match($actionPatern, $currentAction);
    }
}