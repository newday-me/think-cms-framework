<?php
namespace cms\files;

use cms\interfaces\FileInterface;

class UploadFile extends LocalFile
{

    /**
     *
     * {@inheritdoc}
     *
     * @see FileInterface::load()
     */
    public function load($file)
    {
        if ($file['error'] > 0) {
            switch ($file['error']) {
                case 1:
                    $msg = '文件超过服务器允许上传的大小';
                    break;
                case 2:
                    $msg = '文件超过表单允许上传的大小';
                    break;
                case 3:
                    $msg = '文件只有部分被上传';
                    break;
                case 4:
                    $msg = '没有找到要上传的文件';
                    break;
                case 5:
                    $msg = '服务器临时文件夹丢失';
                    break;
                case 6:
                    $msg = '没有找到临时文件夹';
                    break;
                case 7:
                    $msg = '写入临时文件失败';
                    break;
                case 8:
                    $msg = 'PHP不允许上传文件';
                    break;
                default:
                    $msg = '未知的上传错误';
                    break;
            }
            throw new \Exception($msg);
        }
        return parent::load($file['tmp_name']);
    }

}