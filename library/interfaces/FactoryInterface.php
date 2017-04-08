<?php
namespace cms\interfaces;

interface FactoryInterface
{

    /**
     * 创建工厂对象
     *
     * @param string $type            
     * @param array $option            
     */
    public static function make($type, $option = []);

}