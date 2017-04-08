<?php
namespace cms\widget\amazeui\form;

class ColorForm extends TextForm
{

    /**
     * form
     *
     * @param array $data            
     * @return string
     */
    public static function fetch($data = [])
    {
        if (! isset($data['class'])) {
            $data['class'] = '';
        }
        $data['class'] .= 'nd-color';
        
        return parent::fetch($data);
    }
}