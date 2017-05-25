<?php
namespace cms\widget\amazeui\row;

use cms\widget\amazeui\Row;

class ButtonRow extends Row
{

    /**
     * fetch
     *
     * @param array $data            
     * @return string
     */
    public static function fetch($data = [])
    {
        $data = array_merge(self::$row, $data);
        
        $html = '<a class="am-btn am-btn-link am-btn-xs ' . $data['class'] . '" ' . $data['attr'] . ' href="' . $data['url'] . '"><span class="' . $data['icon'] . '"></span> ' . $data['title'] . '</a>';
        
        return $html;
    }
}