<?php
namespace cms;

use MatthiasMullie\Minify\CSS;
use MatthiasMullie\Minify\JS;
use cms\minify\HtmlMinify;
use cms\traits\InstanceTrait;

class Minify
{
    /**
     * 实例Trait
     */
    use InstanceTrait;

    /**
     * 压缩Html
     *
     * @param string $html            
     * @param boolean $js            
     * @param boolean $css            
     * @return string
     */
    public function html($html, $js = true, $css = true)
    {
        return HtmlMinify::minify($html, $js, $css);
    }

    /**
     * 压缩Css
     *
     * @param string $source            
     * @return string
     */
    public function css($css)
    {
        $minifier = new CSS();
        return $minifier->add($css)->minify();
    }

    /**
     * 压缩Js
     *
     * @param string $js            
     * @return string
     */
    public function js($js)
    {
        $minifier = new JS();
        return $minifier->add($js)->minify();
    }

}