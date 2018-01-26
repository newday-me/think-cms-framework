<?php

namespace cms\upload\file;

use cms\core\exception\FileException;

class UploadFile extends LocalFile
{

    /**
     *
     * {@inheritdoc}
     *
     * @see LocalFile::load()
     */
    public function load($file)
    {
        if ($file['error'] > 0) {
            switch ($file['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $msg = '文件超过服务器允许上传的大小';
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $msg = '文件超过表单允许上传的大小';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $msg = '文件只有部分被上传';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $msg = '没有找到要上传的文件';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $msg = '没有找到临时文件夹';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $msg = '写入临时文件失败';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $msg = 'PHP不允许上传文件';
                    break;
                default:
                    $msg = '未知的上传错误';
                    break;
            }
            throw new FileException($msg);
        }
        return parent::load($file['tmp_name']);
    }

}