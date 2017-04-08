<?php
namespace cms\widget\amazeui\form;

use cms\widget\amazeui\Form;

class FileForm extends Form
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
        $data['uuid'] = md5(serialize($data));
        
        $html = '<div class="am-g am-margin-top-sm">';
        $html .= '<div class="am-u-sm-' . $data['l_sm_num'] . ' am-u-md-' . $data['l_md_num'] . ' am-text-right">' . $data['title'] . '</div>';
        $html .= '<div class="am-u-sm-' . $data['r_sm_num'] . ' am-u-md-' . $data['r_md_num'] . ' am-u-end">';
        
        $html .= '<div class="am-input-group nd-input-file-area">';
        $html .= '<input type="text" class="am-input-sm" name="' . $data['name'] . '" value="' . $data['value'] . '" id="upload_file_' . $data['uuid'] . '" />';
        $html .= '<span class="am-input-group-btn">';
        $html .= '<div class="am-form-group am-form-file">';
        $html .= '<button type="button" class="am-btn am-btn-sm am-btn-default">';
        $html .= '<i class="am-icon-cloud-upload"></i>';
        $html .= '<span>选择文件</span>';
        $html .= '</button>';
        $html .= '<input type="file" class="nd-upload-file" nd-target="upload_file_' . $data['uuid'] . '" nd-option=\'' . $data['option'] . '\' />';
        $html .= '</div>';
        $html .= '</span>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}