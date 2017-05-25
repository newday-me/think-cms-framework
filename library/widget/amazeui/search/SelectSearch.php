<?php
namespace cms\widget\amazeui\search;

use cms\widget\amazeui\Search;

class SelectSearch extends Search
{

    /**
     * fetch
     *
     * @param array $data            
     * @return string
     */
    public static function fetch($data = [])
    {
        $data = array_merge(self::$search, $data);
        
        $html = '<div class="am-u-sm-' . $data['sm_num'] . ' am-u-md-' . $data['md_num'] . ' am-u-end">';
        $html .= '<div class="am-form-group">';
        $html .= '<select name="' . $data['name'] . '" class="nd-search-field" data-am-selected="{btnSize: \'sm\', searchBox: 1, maxHeight: 300}">';
        if ($data['all']) {
            $html .= '<option value="**">不限</option>';
        }
        foreach ($data['list'] as $vo) {
            if ($data['value'] === $vo['value']) {
                $html .= '<option selected value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            } else {
                $html .= '<option value="' . $vo['value'] . '">' . $vo['name'] . '</option>';
            }
        }
        $html .= '</select>';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}