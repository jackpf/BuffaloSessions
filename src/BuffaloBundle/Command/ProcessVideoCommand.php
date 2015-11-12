<?php

namespace BuffaloBundle\Command;

use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessVideoCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('process:video')
            ->setDescription('Process next video in the video queue')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        try {
            $queueItem = $em->getRepository('BuffaloBundle:VideoQueueItem')
                ->createQueryBuilder('i')
                ->select('i')
                ->orderBy('i.createdAt', 'ASC')
                ->setMaxResults(1)
                ->getQuery()->getSingleResult();
        } catch (NoResultException $e) {
            $output->writeln('No items in queue');
            return ;
        }

        $processor = $this->getContainer()->get('buffalo.video_processor');
        $result = $processor->process($queueItem->getFile());

        if ($result == 0) {
            $em->remove($queueItem);
            $em->flush();
        }

        $output->writeln('Finished with result: ' . $result);
    }
}