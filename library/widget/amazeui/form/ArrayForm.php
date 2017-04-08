<?php
namespace cms\widget\amazeui\form;

use cms\widget\amazeui\Form;

class ArrayForm extends TextareaForm
{

    /**
     * fetch
     *
     * @param array $data            
     * @return string
     */
    public static function fetch($data = [])
    {
        if (! isset($data['class'])) {
            $data['class'] = '';
        }
        
        $data['class'] .= ' nd-editor-json am-hide';
        $data['attr'] = 'nd-target="nd-editor-ace-' . $data['name'] . '"';
        
        return parent::fetch($data);
    }
}