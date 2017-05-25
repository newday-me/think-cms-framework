<?php
namespace cms\widget\amazeui\row;

use cms\widget\amazeui\Row;

class SelectRow extends Row
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
        
        $html = '<select name="' . $data['name'] . '" class="nd-input" url=' . $data['url'] . ' data-am-selected="{btnSize: \'sm\', searchBox: 1, maxHeight: 300}">';
        foreach ($data['list'] as $vo) {
            if ($data['value'] === $vo['value']) {
                $html .= '<option selected value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            } else {
                $html .= '<option value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            }
        }
        $html .= '</select>';
        
        return $html;
    }
}