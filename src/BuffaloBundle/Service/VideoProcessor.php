<?php

namespace BuffaloBundle\Service;

use BuffaloBundle\Entity\MediaFile;
use BuffaloBundle\Data\Data;
use Doctrine\ORM\EntityManagerInterface;

class VideoProcessor extends Processor
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function process(MediaFile $file)
    {
        $audioPath = $file->getAudioPath();
        $videoPath = $file->getVideoPath();
        $processedPath = DownloadManager::createPath($videoPath);
die(var_dump(sprintf(
    'ffmpeg -i %s -i %s -shortest -strict -2 %s',
    Data::getUploadPath() . '/' . $audioPath,
    Data::getUploadPath() . '/' . $videoPath,
    Data::getUploadPath() . '/' . $processedPath
)));
        $result = self::run(sprintf(
            'ffmpeg -i %s -i %s -shortest -strict -2 %s',
            Data::getUploadPath() . '/' . $audioPath,
            Data::getUploadPath() . '/' . $videoPath,
            Data::getUploadPath() . '/' . $processedPath
        ));

        $file->setProcessedPath($processedPath);
        $this->em->flush();

        return $result;
    }
}