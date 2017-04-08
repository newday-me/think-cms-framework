<?php
namespace cms\widget\amazeui\search;

class DateSearch extends TextSearch
{

    /**
     * fetch
     *
     * @param array $data            
     * @return string
     */
    public static function fetch($data = [])
    {
        if (isset($data['format']) && ! empty($data['format'])) {
            $data['attr'] = 'data-am-datepicker="{format: \'' . $data['format'] . '\'}"';
        } else {
            $data['attr'] = 'data-am-datepicker';
        }
        
        return parent::fetch($data);
    }
}