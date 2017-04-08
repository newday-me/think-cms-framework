<?php
namespace cms\widget\amazeui\form;

class EditorForm extends TextareaForm
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
        $data['class'] .= 'nd-editor-html';
        
        $data['attr'] = 'nd-target="' . $data['name'] . '"';
        
        $data['rows'] = 30;
        
        return parent::fetch($data);
    }
}