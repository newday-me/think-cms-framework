<?php
namespace cms\widget\amazeui\form;

use cms\widget\amazeui\Form;

class ImageForm extends Form
{

    /**
     * fetch
     *
     * @param array $data            
     * @return string
     */
    public static function fetch($data = [])
    {
        if (! isset($data['r_md_num'])) {
            $data['r_md_num'] = 3;
        }
        
        $data = array_merge(self::$form, $data);
        
        $option = json_decode($data['option'], true);
        $option = $option ? $option : [];
        if (isset($data['width'])) {
            $option['width'] = $data['width'];
        }
        if (isset($data['height'])) {
            $option['height'] = $data['height'];
        }
        $data['option'] = json_encode($option, JSON_UNESCAPED_UNICODE);
        
        $data['uuid'] = md5(serialize($data));
        
        $html = '<div class="am-g am-margin-top-sm">';
        $html .= '<div class="am-u-sm-' . $data['l_sm_num'] . ' am-u-md-' . $data['l_md_num'] . ' am-text-right">' . $data['title'] . '</div>';
        $html .= '<div class="am-u-sm-' . $data['r_sm_num'] . ' am-u-md-' . $data['r_md_num'] . ' am-u-end">';
        
        $html .= '<input type="hidden" name="' . $data['name'] . '" value="' . $data['value'] . '" id="upload_file_' . $data['uuid'] . '" />';
        $html .= '<div class="am-form-group am-form-file am-text-center nd-input-image-area" style="background-position: center; background-image: url(\'' . $data['value'] . '\')" id="preview_div_' . $data['uuid'] . '">';
        $html .= '<button type="button" class="am-btn am-btn-default am-btn-sm">';
        $html .= '<i class="am-icon-cloud-upload"></i>';
        $html .= '<span>选择文件</span>';
        $html .= '</button>';
        $html .= '<input type="file" class="nd-upload-file" nd-target="upload_file_' . $data['uuid'] . '" nd-preview="preview_div_' . $data['uuid'] . '" nd-option=\'' . $data['option'] . '\'>';
        $html .= '</div>';
        
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}