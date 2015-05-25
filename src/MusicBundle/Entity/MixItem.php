<?php

namespace MusicBundle\Entity;

class MixItem extends MediaItem
{
    private $mediaFiles = [];

    public function getMediaFiles()
    {
        return $this->mediaFiles;
    }

    public function setMediaFiles($mediaFiles)
    {
        $this->mediaFiles = $mediaFiles;
    }

    public function onPreFlush()
    {
        // Doctrine doesn't seem to be setting the inverse relation
        foreach ($this->mediaFiles as $mediaFile) {
            if ($mediaFile) {
                $mediaFile->setMediaItem($this);
            }
        }
    }
}