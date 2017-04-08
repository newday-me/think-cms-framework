<?php
namespace cms\widget\amazeui;

abstract class Search
{

    /**
     * 搜索配置
     *
     * @param array $data            
     * @var unknown
     */
    protected static $search = [
        'sm_num' => 12,
        'md_num' => 2,
        
        'holder' => '',
        'name' => '',
        'value' => '',
        'list' => [],
        'all' => true,
        
        'class' => '',
        'style' => '',
        'attr' => '',
        
        'text' => '搜索',
        'target' => 'search-form'
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