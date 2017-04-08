<?php
namespace cms;

use cms\traits\InstanceTrait;
use cms\widget\amazeui\form\TextForm;
use cms\widget\amazeui\form\TextareaForm;
use cms\widget\amazeui\form\ArrayForm;
use cms\widget\amazeui\form\CheckboxForm;
use cms\widget\amazeui\form\ColorForm;
use cms\widget\amazeui\form\DateForm;
use cms\widget\amazeui\form\EditorForm;
use cms\widget\amazeui\form\FileForm;
use cms\widget\amazeui\form\ImageForm;
use cms\widget\amazeui\form\RadioForm;
use cms\widget\amazeui\form\SelectForm;
use cms\widget\amazeui\form\SubmitForm;
use cms\widget\amazeui\form\TagFrom;
use cms\widget\amazeui\search\DateSearch;
use cms\widget\amazeui\search\KeywordSearch;
use cms\widget\amazeui\search\SelectSearch;
use cms\widget\amazeui\search\TextSearch;
use cms\widget\amazeui\row\TextRow;
use cms\widget\amazeui\row\ButtonRow;
use cms\widget\amazeui\row\SelectRow;

class Widget
{
    /**
     * 实例Trait
     */
    use InstanceTrait;

    /**
     * 方法名
     *
     * @var unknown
     */
    const FETCH_METHOD = 'fetch';

    /**
     * 表单映射
     *
     * @var unknown
     */
    protected $formMaps = [
        'text' => TextForm::class,
        'textarea' => TextareaForm::class,
        'array' => ArrayForm::class,
        'checkbox' => CheckBoxForm::class,
        'color' => ColorForm::class,
        'date' => DateForm::class,
        'editor' => EditorForm::class,
        'file' => FileForm::class,
        'image' => ImageForm::class,
        'radio' => RadioForm::class,
        'select' => SelectForm::class,
        'submit' => SubmitForm::class,
        'tag' => TagFrom::class
    ];

    /**
     * 搜索映射
     *
     * @var unknown
     */
    protected $searchMaps = [
        'date' => DateSearch::class,
        'keyword' => KeywordSearch::class,
        'select' => SelectSearch::class,
        'text' => TextSearch::class
    ];

    /**
     * 行映射
     *
     * @var unknown
     */
    protected $rowMaps = [
        'text' => TextRow::class,
        'button' => ButtonRow::class,
        'select' => SelectRow::class
    ];

    /**
     * 注册表单映射
     *
     * @param string|array $name            
     * @param string|null $class            
     * @return void
     */
    public function registerFormMaps($name, $class = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->registerFormMaps($key, $value);
            }
        } elseif (is_null($class)) {
            unset($this->formMaps[$name]);
        } else {
            $this->formMaps[$name] = $class;
        }
    }

    /**
     * 构造表单
     *
     * @param string $name            
     * @param array $data            
     * @return string
     */
    public function form($name, $data = [])
    {
        $method = self::FETCH_METHOD;
        $class = isset($this->formMaps[$name]) ? $this->formMaps[$name] : '';
        if ($class && class_exists($class) && method_exists($class, $method)) {
            return $class::$method($data);
        }
        return '';
    }

    /**
     * 注册搜索映射
     *
     * @param string|array $name            
     * @param string|null $class            
     * @return void
     */
    public function registerSearchMaps($name, $class = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->registerSearchMaps($key, $value);
            }
        } elseif (is_null($class)) {
            unset($this->searchMaps[$name]);
        } else {
            $this->searchMaps[$name] = $class;
        }
    }

    /**
     * 构造搜索
     *
     * @param string $name            
     * @param array $data            
     * @return string
     */
    public function search($name, $data = [])
    {
        $method = self::FETCH_METHOD;
        $class = isset($this->searchMaps[$name]) ? $this->searchMaps[$name] : '';
        if ($class && class_exists($class) && method_exists($class, $method)) {
            return $class::$method($data);
        }
        return '';
    }

    /**
     * 注册行映射
     *
     * @param string|array $name            
     * @param string|null $class            
     * @return void
     */
    public function registerRowMaps($name, $class = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $value) {
                $this->registerRowMaps($key, $value);
            }
        } elseif (is_null($class)) {
            unset($this->rowMaps[$name]);
        } else {
            $this->rowMaps[$name] = $class;
        }
    }

    /**
     * 构造搜索
     *
     * @param string $name            
     * @param array $data            
     * @return string
     */
    public function row($name, $data = [])
    {
        $method = self::FETCH_METHOD;
        $class = isset($this->rowMaps[$name]) ? $this->rowMaps[$name] : '';
        if ($class && class_exists($class) && method_exists($class, $method)) {
            return $class::$method($data);
        }
        return '';
    }
}