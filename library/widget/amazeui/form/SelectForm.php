<?php
namespace cms\widget\amazeui\form;

use cms\widget\amazeui\Form;

class SelectForm extends Form
{

    /**
     * fetch
     *
     * @param array $data            
     * @return string
     */
    public static function fetch($data = [])
    {
        $data = array_merge(self::$form, $data);
        
        $html = '<div class="am-g am-margin-top-sm">';
        $html .= '<div class="am-u-sm-' . $data['l_sm_num'] . ' am-u-md-' . $data['l_md_num'] . ' am-text-right">' . $data['title'] . '</div>';
        $html .= '<div class="am-u-sm-' . $data['r_sm_num'] . ' am-u-md-' . $data['r_md_num'] . ' am-u-end">';
        $html .= '<select name="' . $data['name'] . '" ' . $data['attr'] . ' data-am-selected="{btnSize: \'sm\', searchBox: 1, maxHeight: 300}">';
        foreach ($data['list'] as $vo) {
            if (is_array($data['value']) ? in_array($vo['value'], $data['value']) : $data['value'] === $vo['value']) {
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