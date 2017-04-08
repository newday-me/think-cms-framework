<?php
namespace cms\widget\amazeui\form;

class TagFrom extends TextForm
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
        $data['class'] .= 'nd-tag';
        
        return TextForm::fetch($data);
    }
}