<?php
namespace cms\widget\amazeui;

abstract class Row
{

    /**
     * 表单配置
     *
     * @var unknown
     */
    protected static $row = [
        'type' => 'text',
        'holder' => '',
        'name' => '',
        'url' => '',
        'value' => '',
        'list' => [],
        
        'class' => '',
        'style' => '',
        'attr' => '',
        'option' => '',
        'icon' => 'am-icon-pencil-square-o'
    ];

    /**
     * fetch
     *
     * @param array $data            
     * @return string
     */
    public static function fetch($data = [])
    {
        return '';
    }
}