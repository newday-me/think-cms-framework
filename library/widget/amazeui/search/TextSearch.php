<?php
namespace cms\widget\amazeui\search;

use cms\widget\amazeui\Search;

class TextSearch extends Search
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
        
        $html = '<div class="am-u-sm-' . $data['sm_num'] . ' am-u-md-' . $data['md_num'] . '">';
        $html .= '<div class="am-form-group am-input-group-sm">';
        $html .= '<input type="text" class="am-form-field nd-search-field" name="' . $data['name'] . '" placeholder="' . $data['holder'] . '" value="' . $data['value'] . '" style="' . $data['style'] . '" ' . $data['attr'] . ' />';
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}