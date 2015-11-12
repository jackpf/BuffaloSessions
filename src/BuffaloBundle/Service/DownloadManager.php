<?php

namespace BuffaloBundle\Service;

use BuffaloBundle\Data\Data;

class DownloadManager
{
    public static function createName($path, $name)
    {
        return $name . substr($path, strrpos($path, '.'));
    }

    public static function createPath($original)
    {
        $parts = explode('.', $original);
        $ext = end($parts);
        return sha1($original + rand()) . '.' . $ext;
    }
}