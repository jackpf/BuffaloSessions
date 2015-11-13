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
        $processedPath = DownloadManager::createPath($videoPath, 'mp4');

        $result = self::run(sprintf(
            'ffmpeg -itsoffset %f -i %s -i %s -strict -2 %s -c:v libx264 -preset ultrafast -crf 23 -y',
            (float) $file->getAudioDelay(),
            Data::getUploadPath() . '/' . $audioPath,
            Data::getUploadPath() . '/' . $videoPath,
            Data::getUploadPath() . '/' . $processedPath
        ));

        if ($file->getProcessedPath() != null) {
            $file->delete($file->getProcessedPath());
        }

        $file->setProcessedPath($processedPath);
        $this->em->flush();

        return $result;
    }
}