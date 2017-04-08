<?php
namespace cms\widget\amazeui\row;

use cms\widget\amazeui\Row;

class TextRow extends Row
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
        
        $html = '<input type="' . $data['type'] . '" class="am-input-sm nd-input ' . $data['class'] . '" name="' . $data['name'] . '" url="' . $data['url'] . '" value="' . $data['value'] . '" placeholder="' . $data['holder'] . '" style="' . $data['style'] . '" ' . $data['attr'] . ' />';
        
        return $html;
    }
}