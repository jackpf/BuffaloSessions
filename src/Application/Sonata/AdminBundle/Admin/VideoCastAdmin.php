<?php

namespace Application\Sonata\AdminBundle\Admin;

use BuffaloBundle\Entity\VideoQueueItem;
use BuffaloBundle\Service\VideoProcessor;
use Doctrine\ORM\EntityManagerInterface;
use MusicBundle\Data\Data;
use MusicBundle\Service\AudioProcessor;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class VideoCastAdmin extends MediaAdmin
{
    private $em;

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        parent::configureFormFields($formMapper);

        $formMapper
            ->add('videoFile', 'sonata_type_admin', [
                'required' => true,
                'cascade_validation' => true,
            ])
        ;
    }

    public function setEntityManager(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function preUpdate($object)
    {
        parent::preUpdate($object);

        // Delete?
        if ($this->getRequest()->request->get($this->getUniqid())['videoFile']['_delete']) {
            $this->em->remove($object->getVideoFile());
            $this->em->flush();
        }

        // Make sure life cycle events are triggered
        if ($file = $object->getVideoFile()) {
            $file->setUpdatedAt(new \DateTime());
        }
    }

    public function postPersist($object)
    {
        parent::postPersist($object);

        $this->processVideo($object);
    }

    public function postUpdate($object)
    {
        parent::postUpdate($object);

        $this->processVideo($object);
    }

    public function processVideo($object)
    {
        $file = $object->getVideoFile();

        if ($file->getAudioFile() || $file->getVideoFile()) {
            $existingQueueItem = $this->em->getRepository('BuffaloBundle:VideoQueueItem')
                ->findByFile($file);

            if (!$existingQueueItem) {
                $videoQueueItem = new VideoQueueItem();
                $videoQueueItem->setFile($file);
                $this->em->persist($videoQueueItem);

                $this->em->flush();
            }
        }
    }
}