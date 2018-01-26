<?php

namespace cms\upload\file;

use cms\core\exception\FileException;

class RemoteFile extends StreamFile
{

    /**
     *
     * {@inheritdoc}
     *
     * @see StreamFile::load()
     */
    public function load($file)
    {
        $stream = $this->fetchUrl($file);
        if (empty($stream)) {
            throw new FileException('抓取远程文件[' . $file . ']失败');
        }

        return parent::load($stream);
    }

    /**
     * 抓取远程文件
     *
     * @param string $url
     *
     * @return string|null
     */
    public function fetchUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // HTTPS
        if (strpos($url, 'https://') !== false) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $res = curl_exec($ch);
        curl_close($ch);

        return $res;
    }
}