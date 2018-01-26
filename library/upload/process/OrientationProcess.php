<?php

namespace cms\upload\process;

use cms\upload\Process;
use cms\core\interfaces\FileInterface;
use cms\core\interfaces\FileProcessInterface;

class OrientationProcess extends Process
{

    /**
     *
     * {@inheritdoc}
     *
     * @see FileProcessInterface::process()
     */
    public function process(FileInterface $file)
    {
        // Exif扩展
        if (!extension_loaded('exif')) {
            return true;
        }

        // 文件后缀
        $extensions = [
            'jpg',
            'jpeg'
        ];
        if (!in_array($file->getExtension(), $extensions)) {
            return true;
        }

        // 图片信息
        $exif = exif_read_data($file->getPath());
        if (empty($exif['Orientation'])) {
            return true;
        }

        // 旋转图片
        $image = imagecreatefromstring($file->getContent());
        switch ($exif['Orientation']) {
            case 8:
                $image = imagerotate($image, 90, 0);
                break;
            case 3:
                $image = imagerotate($image, 180, 0);
                break;
            case 6:
                $image = imagerotate($image, -90, 0);
                break;
        }
        return imagejpeg($image, $file->getPath());
    }

}