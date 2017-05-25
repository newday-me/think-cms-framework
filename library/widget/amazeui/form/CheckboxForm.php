<?php
namespace cms\widget\amazeui\form;

use cms\widget\amazeui\Form;

class CheckboxForm extends Form
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
        $html .= '<div class="am-u-sm-' . $data['l_sm_num'] . ' am-u-md-' . $data['l_md_num'] . ' am-text-right ' . $data['l_class'] . '" style="' . $data['l_style'] . '" ' . $data['l_attr'] . '>' . $data['title'] . '</div>';
        $html .= '<div class="am-u-sm-' . $data['r_sm_num'] . ' am-u-md-' . $data['r_md_num'] . ' am-u-end ' . $data['r_class'] . '" style="' . $data['r_style'] . '" ' . $data['l_attr'] . '>';
        $class = $data['inline'] ? 'am-checkbox-inline' : 'am-checkbox';
        foreach ($data['list'] as $vo) {
            $html .= '<label class="' . $class . ' am-secondary">';
            if (is_array($data['value']) ? in_array($vo['value'], $data['value']) : $data['value'] === $vo['value']) {
                $html .= '<input checked type="checkbox" name="' . $data['name'] . '" value="' . $vo['value'] . '" data-am-ucheck /> ' . $vo['name'];
            } else {
                $html .= '<input type="checkbox" name="' . $data['name'] . '" value="' . $vo['value'] . '" data-am-ucheck /> ' . $vo['name'];
            }
            $html .= '</label>';
        }
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}