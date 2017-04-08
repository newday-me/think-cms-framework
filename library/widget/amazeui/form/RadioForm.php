<?php
namespace cms\widget\amazeui\form;

use cms\widget\amazeui\Form;

class RadioForm extends Form
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
        foreach ($data['list'] as $vo) {
            $html .= '<label class="am-radio-inline am-secondary">';
            if ($data['value'] === $vo['value']) {
                $html .= '<input checked type="radio" name="' . $data['name'] . '" value="' . $vo['value'] . '" data-am-ucheck /> ' . $vo['name'];
            } else {
                $html .= '<input type="radio" name="' . $data['name'] . '" value="' . $vo['value'] . '" data-am-ucheck /> ' . $vo['name'];
            }
            $html .= '</label>';
        }
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}