<?php

namespace BuffaloBundle\Entity;

use BuffaloBundle\Data\Data;
use BuffaloBundle\Service\DownloadManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaFile
{
    private $id;

    private $name;

    private $processedPath;

    private $videoPath;

    private $audioPath;

    private $mediaItem;

    private $videoFile;

    private $audioFile;

    private $createdAt;

    private $updatedAt;

    private $audioDelay;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getProcessedPath()
    {
        return $this->processedPath;
    }

    /**
     * @param mixed $processedPath
     */
    public function setProcessedPath($processedPath)
    {
        $this->processedPath = $processedPath;
    }

    /**
     * @return mixed
     */
    public function getVideoPath()
    {
        return $this->videoPath;
    }

    /**
     * @param mixed $videoPath
     */
    public function setVideoPath($videoPath)
    {
        $this->videoPath = $videoPath;
    }

    /**
     * @return mixed
     */
    public function getAudioPath()
    {
        return $this->audioPath;
    }

    /**
     * @param mixed $audioPath
     */
    public function setAudioPath($audioPath)
    {
        $this->audioPath = $audioPath;
    }

    /**
     * @return mixed
     */
    public function getMediaItem()
    {
        return $this->mediaItem;
    }

    /**
     * @param mixed $mediaItem
     */
    public function setMediaItem($mediaItem)
    {
        $this->mediaItem = $mediaItem;
    }

    /**
     * @return mixed
     */
    public function getVideoFile()
    {
        return $this->videoFile;
    }

    /**
     * @param mixed $videoFile
     */
    public function setVideoFile($videoFile)
    {
        $this->videoFile = $videoFile;
    }

    /**
     * @return mixed
     */
    public function getAudioFile()
    {
        return $this->audioFile;
    }

    /**
     * @param mixed $audioFile
     */
    public function setAudioFile($audioFile)
    {
        $this->audioFile = $audioFile;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getAudioDelay()
    {
        return $this->audioDelay;
    }

    /**
     * @param mixed $audioDelay
     */
    public function setAudioDelay($audioDelay)
    {
        $this->audioDelay = $audioDelay;
    }

    public function lifecycleFileUpload()
    {
        if ($this->getAudioFile()) {
            $this->delete($this->getAudioPath());

            $path = DownloadManager::createPath($this->getAudioFile()->getClientOriginalName());

            $this->getAudioFile()->move(
                Data::getUploadPath(),
                $path
            );
            $this->setAudioPath($path);
        }

        if ($this->getVideoFile()) {
            $this->delete($this->getVideoPath());

            $path = DownloadManager::createPath($this->getVideoFile()->getClientOriginalName());

            $this->getVideoFile()->move(
                Data::getUploadPath(),
                $path
            );
            $this->setVideoPath($path);
        }
    }

    public function lifecycleFileDelete()
    {
        $this->delete($this->getAudioPath());
        $this->delete($this->getVideoPath());
    }

    public function delete($filename)
    {
        $path = Data::getUploadPath() . '/' . $filename;

        if ($filename != null && file_exists($path)) {
            unlink($path);
        }
    }
}